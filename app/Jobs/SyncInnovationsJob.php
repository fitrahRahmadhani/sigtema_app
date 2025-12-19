<?php

namespace App\Jobs;

use App\Models\Employee;
use App\Models\Innovation;
use App\Events\SyncStatusUpdated;
use App\Events\SyncProgressUpdated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncInnovationsJob implements ShouldQueue
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
        $lockKey = "innovation_sync_lock_" . $this->userId;

        // Cek lock untuk mencegah multiple sync
        if (Cache::has($lockKey)) {
            Log::warning('Sync already in progress', ['user_id' => $this->userId]);
            return;
        }

        // Set lock untuk 1 jam
        Cache::put($lockKey, true, 3600);

        try {
            Log::info('Starting innovation sync job', ['user_id' => $this->userId]);

            // Kirim event status processing
            broadcast(new SyncStatusUpdated($this->userId, 'processing', null, 'Starting sync process...'));

            // Fetch data dari API
            broadcast(new SyncProgressUpdated($this->userId, 10, 'Fetching data from API...'));
            $apiData = $this->fetchApiData();

            if (empty($apiData)) {
                throw new \Exception('No data received from API');
            }

            // Kirim event progress
            broadcast(new SyncProgressUpdated($this->userId, 30, 'Processing ' . count($apiData) . ' items...', 0, count($apiData)));

            // Process data
            $stats = $this->processData($apiData);

            Log::info('Sync job completed', $stats);

            // Kirim event completed
            broadcast(new SyncStatusUpdated($this->userId, 'completed', $stats, 'Sync completed successfully!'));

            $this->saveSyncResult($stats);
        } catch (\Exception $e) {
            Log::error('Sync job failed', [
                'user_id' => $this->userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Kirim event failed
            broadcast(new SyncStatusUpdated($this->userId, 'failed', ['error' => $e->getMessage()], 'Sync failed: ' . $e->getMessage()));

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
     * Fetch data dari API dengan validation
     */
    private function fetchApiData(): array
    {
        $apiUrl = config('services.innovation_api.url');
        $apiKey = config('services.innovation_api.key');

        if (!$apiUrl || !$apiKey) {
            throw new \Exception('API configuration not found');
        }

        Log::info('Fetching data from API', ['url' => $apiUrl]);

        broadcast(new SyncProgressUpdated($this->userId, 15, 'Connecting to API...'));

        $response = Http::withHeaders([
            'x-api-key' => $apiKey,
            'Accept' => 'application/json',
        ])->timeout(300)->get($apiUrl);

        broadcast(new SyncProgressUpdated($this->userId, 20, 'Receiving data from API...'));

        if (!$response->successful()) {
            $errorMessage = 'API request failed: ' . $response->status();
            Log::error('API request failed', [
                'status' => $response->status(),
                'url' => $apiUrl,
                'response' => $response->body()
            ]);
            throw new \Exception($errorMessage);
        }

        $data = $response->json();

        // Validasi struktur data
        if (!is_array($data)) {
            throw new \Exception('Invalid API response: expected array');
        }

        // Jika ada key 'data', gunakan itu
        if (isset($data['data']) && is_array($data['data'])) {
            $rawData = $data['data'];
        } else {
            $rawData = $data;
        }

        if (empty($rawData)) {
            Log::warning('Empty data received from API');
            return [];
        }

        // Validasi setiap item
        $validatedData = [];
        foreach ($rawData as $index => $item) {
            if (!is_array($item)) {
                Log::warning('Invalid item format at index ' . $index);
                continue;
            }

            // Validasi field required
            if (empty($item['employee_id']) || empty($item['judul_inovasi'])) {
                Log::warning('Missing required fields at index ' . $index, [
                    'employee_id' => $item['employee_id'] ?? 'missing',
                    'judul_inovasi' => $item['judul_inovasi'] ?? 'missing'
                ]);
                continue;
            }

            $validatedData[] = $item;
        }

        broadcast(new SyncProgressUpdated($this->userId, 25, 'Validated ' . count($validatedData) . ' items'));
        Log::info('API data validated', ['total' => count($validatedData)]);

        return $validatedData;
    }

    /**
     * Process data dengan is_active flag
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
            broadcast(new SyncProgressUpdated($this->userId, 35, 'Deactivating existing external innovations...'));

            $deactivatedCount = Innovation::where('source', 'eksternal')
                ->where('is_active', true)
                ->update(['is_active' => false]);

            $stats['deactivated'] = $deactivatedCount;

            broadcast(new SyncProgressUpdated($this->userId, 40, "Deactivated {$deactivatedCount} innovations"));

            // 2. Group data by unique key untuk menghindari duplikasi
            $groupedData = collect($apiData)->groupBy(function ($item) {
                return $this->padEmployeeId($item['employee_id']) . '|' .
                    trim($item['judul_inovasi']) . '|' .
                    ($item['event'] ?? '');
            });

            $totalGroups = count($groupedData);
            $processed = 0;

            // 3. Process data dari API
            foreach ($groupedData as $uniqueKey => $items) {
                try {
                    // Ambil item pertama dari group
                    $data = $items->first();

                    // Format employee_id
                    $employeeNumber = $this->padEmployeeId($data['employee_id']);

                    // Cari employee
                    $employee = Employee::where('employee_number', $employeeNumber)->first();

                    // Buat unique identifier
                    $uniqueIdentifier = md5($employeeNumber . trim($data['judul_inovasi']) . ($data['event'] ?? ''));

                    // Update atau create innovation
                    $innovation = Innovation::updateOrCreate(
                        [
                            'source' => 'eksternal',
                            'external_id' => $uniqueIdentifier,
                        ],
                        [
                            'employee_id' => $employee?->id,
                            'employee_number' => $employeeNumber,
                            'title' => trim($data['judul_inovasi']),
                            'team' => $data['team'] ?? null,
                            'theme' => $data['tema'] ?? null,
                            'event' => $data['event'] ?? null,
                            'category' => $data['kategori'] ?? null,
                            'replication' => $data['potensi_replikasi'] ?? null,
                            'status' => $data['status'] ?? null,
                            'achievement' => $data['penghargaan'] ?? null,
                            'is_active' => true,
                            'updated_at' => now()
                        ]
                    );

                    $innovation->wasRecentlyCreated ? $stats['created']++ : $stats['updated']++;
                    $processed++;

                    // Update progress setiap 10 item atau 10% dari total
                    if ($processed % 10 === 0 || $processed === $totalGroups) {
                        $progress = 40 + (($processed / $totalGroups) * 50);
                        broadcast(new SyncProgressUpdated(
                            $this->userId,
                            (int) $progress,
                            "Processed {$processed} of {$totalGroups} innovations",
                            $processed,
                            $totalGroups
                        ));
                    }
                } catch (\Exception $e) {
                    $stats['skipped']++;
                    Log::warning('Failed to process innovation', [
                        'unique_key' => $uniqueKey,
                        'error' => $e->getMessage(),
                        'data' => $data ?? []
                    ]);
                }
            }

            // 4. Hapus data yang sudah tidak aktif dalam waktu lama
            broadcast(new SyncProgressUpdated($this->userId, 92, 'Cleaning up old inactive data...'));

            $thirtyDaysAgo = now()->subDays(30);
            $permanentlyDeleted = Innovation::where('source', 'eksternal')
                ->where('is_active', false)
                ->where('updated_at', '<', $thirtyDaysAgo)
                ->delete();

            $stats['permanently_deleted'] = $permanentlyDeleted;

            broadcast(new SyncProgressUpdated($this->userId, 96, "Cleaned up {$permanentlyDeleted} old records"));
        });

        broadcast(new SyncProgressUpdated($this->userId, 98, 'Finalizing sync...'));

        $stats['completed_at'] = now();
        $stats['status'] = 'completed';

        return $stats;
    }

    /**
     * Format employee_id menjadi 8 digit
     */
    private function padEmployeeId($employeeId): string
    {
        if (is_numeric($employeeId)) {
            return str_pad((string) intval($employeeId), 8, '0', STR_PAD_LEFT);
        }
        return str_pad(trim($employeeId), 8, '0', STR_PAD_LEFT);
    }

    /**
     * Simpan hasil sync
     */
    private function saveSyncResult(array $stats): void
    {
        Cache::put("last_sync_result_{$this->userId}", $stats, 86400);
        Cache::put("sync_status_{$this->userId}", $stats['status'], 86400);
    }

    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('SyncInnovationsJob failed after all retries', [
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
        Cache::forget("innovation_sync_lock_" . $this->userId);

        // Kirim event failed
        broadcast(new SyncStatusUpdated(
            $this->userId,
            'failed',
            ['error' => $exception->getMessage()],
            'Sync failed: ' . $exception->getMessage()
        ));
    }
}
