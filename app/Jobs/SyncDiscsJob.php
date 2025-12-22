<?php

namespace App\Jobs;

use App\Models\Employee;
use App\Models\Disc;
use App\Events\DiscSyncStatusUpdated;
use App\Events\DiscSyncProgressUpdated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncDiscsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userId;
    public $tries = 3;
    public $timeout = 1800;
    public $deleteWhenMissingModels = true;

    public function __construct($userId = null)
    {
        $this->userId = $userId;
    }

    public function handle()
    {
        $lockKey = "disc_sync_lock_" . $this->userId;

        // Cek lock untuk mencegah multiple sync
        if (Cache::has($lockKey)) {
            Log::warning('DISC sync already in progress', ['user_id' => $this->userId]);
            return;
        }

        // Set lock untuk 1 jam
        Cache::put($lockKey, true, 3600);

        try {
            Log::info('Starting DISC sync job', ['user_id' => $this->userId]);

            // Kirim event status processing
            broadcast(new DiscSyncStatusUpdated($this->userId, 'processing', null, 'Starting DISC sync process...'));

            // Fetch data dari API
            broadcast(new DiscSyncProgressUpdated($this->userId, 10, 'Fetching DISC data from API...'));
            $apiData = $this->fetchApiData();

            if (empty($apiData)) {
                throw new \Exception('No DISC data received from API');
            }

            // Kirim event progress
            broadcast(new DiscSyncProgressUpdated($this->userId, 30, 'Processing ' . count($apiData) . ' DISC assessments...', 0, count($apiData)));

            // Process data
            $stats = $this->processData($apiData);

            Log::info('DISC sync job completed', $stats);

            // Kirim event completed
            broadcast(new DiscSyncStatusUpdated($this->userId, 'completed', $stats, 'DISC sync completed successfully!'));

            $this->saveSyncResult($stats);
        } catch (\Exception $e) {
            Log::error('DISC sync job failed', [
                'user_id' => $this->userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Kirim event failed
            broadcast(new DiscSyncStatusUpdated($this->userId, 'failed', ['error' => $e->getMessage()], 'DISC sync failed: ' . $e->getMessage()));

            $this->saveSyncResult([
                'status' => 'failed',
                'error' => $e->getMessage(),
                'completed_at' => now()
            ]);

            throw $e;
        } finally {
            // Release lock
            Cache::forget($lockKey);
        }
    }

    /**
     * Fetch data dari API DISC
     */
    private function fetchApiData(): array
    {
        $apiUrl = config('services.disc_api.url');
        $apiKey = config('services.disc_api.key');

        if (!$apiUrl) {
            throw new \Exception('DISC API configuration not found');
        }

        Log::info('Fetching DISC data from API', ['url' => $apiUrl]);

        broadcast(new DiscSyncProgressUpdated($this->userId, 15, 'Connecting to DISC API...'));

        $headers = [
            'Accept' => 'application/json',
        ];

        // Tambahkan API key jika ada
        if ($apiKey) {
            $headers['x-api-key'] = $apiKey;
            $headers['Authorization'] = 'Bearer ' . $apiKey;
        }

        $response = Http::withHeaders($headers)
            ->timeout(300)
            ->get($apiUrl);

        broadcast(new DiscSyncProgressUpdated($this->userId, 20, 'Receiving DISC data from API...'));

        if (!$response->successful()) {
            $errorMessage = 'DISC API request failed: ' . $response->status();
            Log::error('DISC API request failed', [
                'status' => $response->status(),
                'url' => $apiUrl,
                'response' => $response->body()
            ]);
            throw new \Exception($errorMessage);
        }

        $data = $response->json();

        // Validasi struktur data
        if (!is_array($data)) {
            throw new \Exception('Invalid DISC API response: expected array');
        }

        // Jika ada key 'data', gunakan itu
        if (isset($data['data']) && is_array($data['data'])) {
            $rawData = $data['data'];
        } else {
            $rawData = $data;
        }

        if (empty($rawData)) {
            Log::warning('Empty DISC data received from API');
            return [];
        }

        // Validasi setiap item
        $validatedData = [];
        foreach ($rawData as $index => $item) {
            if (!is_array($item)) {
                Log::warning('Invalid DISC item format at index ' . $index);
                continue;
            }

            // Validasi field required minimal
            if (empty($item['employee_number'])) {
                Log::warning('Missing employee_number at index ' . $index);
                continue;
            }

            $validatedData[] = $item;
        }

        broadcast(new DiscSyncProgressUpdated($this->userId, 25, 'Validated ' . count($validatedData) . ' DISC assessments'));
        Log::info('DISC API data validated', ['total' => count($validatedData)]);

        return $validatedData;
    }

    /**
     * Process data DISC dengan is_active logic
     */
    private function processData(array $apiData): array
    {
        $stats = [
            'total' => count($apiData),
            'created' => 0,
            'updated' => 0,
            'deactivated' => 0,
            'skipped' => 0,
            'permanently_deleted' => 0,
            'started_at' => now(),
            'completed_at' => null,
            'status' => 'processing'
        ];

        DB::transaction(function () use ($apiData, &$stats) {
            // 1. Reset semua data external menjadi tidak aktif terlebih dahulu
            broadcast(new DiscSyncProgressUpdated($this->userId, 35, 'Deactivating existing external DISC assessments...'));

            $deactivatedCount = Disc::where('source', 'eksternal')
                ->where('is_active', true)
                ->update(['is_active' => false]);

            $stats['deactivated'] = $deactivatedCount;

            broadcast(new DiscSyncProgressUpdated($this->userId, 40, "Deactivated {$deactivatedCount} DISC assessments"));

            $totalItems = count($apiData);
            $processed = 0;

            // 2. Process data dari API
            foreach ($apiData as $data) {
                try {
                    if (empty($data['employee_number'])) {
                        $stats['skipped']++;
                        continue;
                    }

                    // Format employee_number
                    $employeeNumber = $this->formatEmployeeNumber($data['employee_number']);

                    // Cari employee
                    $employee = Employee::where('employee_number', $employeeNumber)->first();

                    // Buat unique identifier
                    $uniqueIdentifier = md5($employeeNumber .
                        ($data['assessment_date'] ?? '') .
                        json_encode([
                            $data['D1'] ?? null,
                            $data['I1'] ?? null,
                            $data['S1'] ?? null,
                            $data['C1'] ?? null,
                            $data['D2'] ?? null,
                            $data['I2'] ?? null,
                            $data['S2'] ?? null,
                            $data['C2'] ?? null,
                            $data['D3'] ?? null,
                            $data['I3'] ?? null,
                            $data['S3'] ?? null,
                            $data['C3'] ?? null,
                        ]));

                    // Update atau create DISC data dengan is_active = true
                    $disc = Disc::updateOrCreate(
                        [
                            'source' => 'eksternal',
                            'external_id' => $uniqueIdentifier,
                        ],
                        [
                            'employee_id' => $employee?->id,
                            'employee_number' => $employeeNumber,
                            'D1' => $this->cleanNumber($data['D1'] ?? null),
                            'I1' => $this->cleanNumber($data['I1'] ?? null),
                            'S1' => $this->cleanNumber($data['S1'] ?? null),
                            'C1' => $this->cleanNumber($data['C1'] ?? null),
                            'D2' => $this->cleanNumber($data['D2'] ?? null),
                            'I2' => $this->cleanNumber($data['I2'] ?? null),
                            'S2' => $this->cleanNumber($data['S2'] ?? null),
                            'C2' => $this->cleanNumber($data['C2'] ?? null),
                            'D3' => $this->cleanNumber($data['D3'] ?? null),
                            'I3' => $this->cleanNumber($data['I3'] ?? null),
                            'S3' => $this->cleanNumber($data['S3'] ?? null),
                            'C3' => $this->cleanNumber($data['C3'] ?? null),
                            'interpretation1' => $this->cleanInterpretation($data['interpretation1'] ?? null),
                            'interpretation2' => $this->cleanInterpretation($data['interpretation2'] ?? null),
                            'interpretation3' => $this->cleanInterpretation($data['interpretation3'] ?? null),
                            'assessment_date' => $data['assessment_date'] ?? null,
                            'is_active' => true,
                            'updated_at' => now()
                        ]
                    );

                    $disc->wasRecentlyCreated ? $stats['created']++ : $stats['updated']++;
                    $processed++;

                    // Update progress setiap 10 item atau 10% dari total
                    if ($processed % 10 === 0 || $processed === $totalItems) {
                        $progress = 40 + (($processed / $totalItems) * 50);
                        broadcast(new DiscSyncProgressUpdated(
                            $this->userId,
                            (int) $progress,
                            "Processed {$processed} of {$totalItems} DISC assessments",
                            $processed,
                            $totalItems
                        ));
                    }
                } catch (\Exception $e) {
                    $stats['skipped']++;
                    Log::warning('Failed to process DISC assessment', [
                        'employee_number' => $data['employee_number'] ?? 'unknown',
                        'error' => $e->getMessage(),
                        'data' => $data
                    ]);
                }
            }

            // 3. Hapus data yang sudah tidak aktif dalam waktu lama
            broadcast(new DiscSyncProgressUpdated($this->userId, 92, 'Cleaning up old inactive DISC data...'));

            $thirtyDaysAgo = now()->subDays(30);
            $permanentlyDeleted = Disc::where('source', 'eksternal')
                ->where('is_active', false)
                ->where('updated_at', '<', $thirtyDaysAgo)
                ->delete();

            $stats['permanently_deleted'] = $permanentlyDeleted;

            broadcast(new DiscSyncProgressUpdated($this->userId, 96, "Cleaned up {$permanentlyDeleted} old DISC records"));
        });

        broadcast(new DiscSyncProgressUpdated($this->userId, 98, 'Finalizing DISC sync...'));

        $stats['completed_at'] = now();
        $stats['status'] = 'completed';

        return $stats;
    }

    /**
     * Format employee_number menjadi 8 digit
     */
    private function formatEmployeeNumber($employeeNumber): string
    {
        if (is_numeric($employeeNumber)) {
            return str_pad((string) intval($employeeNumber), 8, '0', STR_PAD_LEFT);
        }
        return str_pad(trim($employeeNumber), 8, '0', STR_PAD_LEFT);
    }

    /**
     * Clean number value
     */
    private function cleanNumber($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            $intValue = (int) $value;
            // Validasi range DISC scores (biasanya 0-100 atau 0-10)
            return max(0, min(100, $intValue));
        }

        return null;
    }

    /**
     * Clean interpretation value
     */
    private function cleanInterpretation($value)
    {
        if (empty($value)) {
            return null;
        }

        $value = trim($value);

        // Normalize common DISC interpretations
        $normalized = strtolower($value);
        $interpretations = [
            'dominant' => 'Dominant',
            'influential' => 'Influential',
            'steady' => 'Steady',
            'conscientious' => 'Conscientious',
            'd' => 'Dominant',
            'i' => 'Influential',
            's' => 'Steady',
            'c' => 'Conscientious',
        ];

        return $interpretations[$normalized] ?? ucfirst($value);
    }

    /**
     * Simpan hasil sync
     */
    private function saveSyncResult(array $stats): void
    {
        Cache::put("last_disc_sync_result_{$this->userId}", $stats, 86400);
        Cache::put("disc_sync_status_{$this->userId}", $stats['status'], 86400);
    }

    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('SyncDiscsJob failed after all retries', [
            'user_id' => $this->userId,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);

        $this->saveSyncResult([
            'status' => 'failed',
            'error' => $exception->getMessage(),
            'completed_at' => now()
        ]);

        // Release lock jika gagal
        Cache::forget("disc_sync_lock_" . $this->userId);

        // Kirim event failed
        broadcast(new DiscSyncStatusUpdated(
            $this->userId,
            'failed',
            ['error' => $exception->getMessage()],
            'DISC sync failed: ' . $exception->getMessage()
        ));
    }
}
