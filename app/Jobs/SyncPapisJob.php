<?php

namespace App\Jobs;

use App\Models\Employee;
use App\Models\Papi;
use App\Events\PapiSyncStatusUpdated;
use App\Events\PapiSyncProgressUpdated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class SyncPapisJob implements ShouldQueue
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
        $lockKey = "papi_sync_lock_" . $this->userId;

        // Cek lock untuk mencegah multiple sync
        if (Cache::has($lockKey)) {
            Log::warning('PAPI sync already in progress', ['user_id' => $this->userId]);
            return;
        }

        // Set lock untuk 1 jam
        Cache::put($lockKey, true, 3600);

        try {
            Log::info('Starting PAPI sync job', ['user_id' => $this->userId]);

            // Kirim event status processing
            broadcast(new PapiSyncStatusUpdated($this->userId, 'processing', null, 'Starting PAPI sync process...'));

            // Fetch data dari API
            broadcast(new PapiSyncProgressUpdated($this->userId, 10, 'Fetching data from API...'));
            $apiData = $this->fetchApiData();

            if (empty($apiData)) {
                throw new \Exception('No data received from API');
            }

            // Kirim event progress
            broadcast(new PapiSyncProgressUpdated($this->userId, 30, 'Processing ' . count($apiData) . ' items...', 0, count($apiData)));

            // Process data
            $stats = $this->processData($apiData);

            Log::info('PAPI sync job completed', $stats);

            // Kirim event completed
            broadcast(new PapiSyncStatusUpdated($this->userId, 'completed', $stats, 'PAPI sync completed successfully!'));

            $this->saveSyncResult($stats);
        } catch (\Exception $e) {
            Log::error('PAPI sync job failed', [
                'user_id' => $this->userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Kirim event failed
            broadcast(new PapiSyncStatusUpdated($this->userId, 'failed', ['error' => $e->getMessage()], 'PAPI sync failed: ' . $e->getMessage()));

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
        $apiUrl = config('services.papi_api.url');
        $apiKey = config('services.papi_api.key');

        if (!$apiUrl || !$apiKey) {
            throw new \Exception('PAPI API configuration not found');
        }

        Log::info('Fetching PAPI data from API', ['url' => $apiUrl]);

        broadcast(new PapiSyncProgressUpdated($this->userId, 15, 'Connecting to API...'));

        $response = Http::withHeaders([
            'x-api-key' => $apiKey,
            'Accept' => 'application/json',
        ])->timeout(300)->get($apiUrl);

        broadcast(new PapiSyncProgressUpdated($this->userId, 20, 'Receiving data from API...'));

        if (!$response->successful()) {
            $errorMessage = 'API request failed: ' . $response->status();
            Log::error('PAPI API request failed', [
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
            Log::warning('Empty data received from PAPI API');
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
            if (empty($item['employee_number'])) {
                Log::warning('Missing required field employee_number at index ' . $index);
                continue;
            }

            // Format employee_number
            $employeeNumber = $this->padEmployeeId($item['employee_number']);
            $item['employee_number'] = $employeeNumber;

            $validatedData[] = $item;
        }

        broadcast(new PapiSyncProgressUpdated($this->userId, 25, 'Validated ' . count($validatedData) . ' items'));
        Log::info('PAPI API data validated', ['total' => count($validatedData)]);

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
            broadcast(new PapiSyncProgressUpdated($this->userId, 35, 'Deactivating existing external PAPI records...'));

            $deactivatedCount = Papi::where('source', 'eksternal')
                ->where('is_active', true)
                ->update(['is_active' => false]);

            $stats['deactivated'] = $deactivatedCount;

            broadcast(new PapiSyncProgressUpdated($this->userId, 40, "Deactivated {$deactivatedCount} PAPI records"));

            // 2. Group data by unique key untuk menghindari duplikasi
            $groupedData = collect($apiData)->groupBy(function ($item) {
                return $this->padEmployeeId($item['employee_number']);
            });

            $totalGroups = count($groupedData);
            $processed = 0;

            // 3. Process data dari API
            foreach ($groupedData as $uniqueKey => $items) {
                try {
                    // Ambil item pertama dari group
                    $data = $items->first();

                    // Format employee_number
                    $employeeNumber = $this->padEmployeeId($data['employee_number']);

                    // Cari employee
                    $employee = Employee::where('employee_number', $employeeNumber)->first();

                    // Buat unique identifier
                    $uniqueIdentifier = md5($employeeNumber . ($data['assessment_date'] ?? '') . now()->format('Ymd'));

                    // Update atau create PAPI
                    $papi = Papi::updateOrCreate(
                        [
                            'source' => 'eksternal',
                            'external_id' => $uniqueIdentifier,
                        ],
                        [
                            'employee_id' => $employee?->id,
                            'employee_number' => $employeeNumber,
                            'N' => $data['N'] ?? null,
                            'G' => $data['G'] ?? null,
                            'A' => $data['A'] ?? null,
                            'L' => $data['L'] ?? null,
                            'P' => $data['P'] ?? null,
                            'I' => $data['I'] ?? null,
                            'T' => $data['T'] ?? null,
                            'V' => $data['V'] ?? null,
                            'S' => $data['S'] ?? null,
                            'B' => $data['B'] ?? null,
                            'O' => $data['O'] ?? null,
                            'X' => $data['X'] ?? null,
                            'C' => $data['C'] ?? null,
                            'D' => $data['D'] ?? null,
                            'R' => $data['R'] ?? null,
                            'Z' => $data['Z'] ?? null,
                            'E' => $data['E'] ?? null,
                            'K' => $data['K'] ?? null,
                            'F' => $data['F'] ?? null,
                            'W' => $data['W'] ?? null,
                            'is_active' => true,
                            'updated_at' => now()
                        ]
                    );

                    $papi->wasRecentlyCreated ? $stats['created']++ : $stats['updated']++;
                    $processed++;

                    // Update progress setiap 10 item atau 10% dari total
                    if ($processed % 10 === 0 || $processed === $totalGroups) {
                        $progress = 40 + (($processed / $totalGroups) * 50);
                        broadcast(new PapiSyncProgressUpdated(
                            $this->userId,
                            (int) $progress,
                            "Processed {$processed} of {$totalGroups} PAPI records",
                            $processed,
                            $totalGroups
                        ));
                    }
                } catch (\Exception $e) {
                    $stats['skipped']++;
                    Log::warning('Failed to process PAPI', [
                        'unique_key' => $uniqueKey,
                        'error' => $e->getMessage(),
                        'data' => $data ?? []
                    ]);
                }
            }

            // 4. Hapus data yang sudah tidak aktif dalam waktu lama
            broadcast(new PapiSyncProgressUpdated($this->userId, 92, 'Cleaning up old inactive data...'));

            $thirtyDaysAgo = now()->subDays(30);
            $permanentlyDeleted = Papi::where('source', 'eksternal')
                ->where('is_active', false)
                ->where('updated_at', '<', $thirtyDaysAgo)
                ->delete();

            $stats['permanently_deleted'] = $permanentlyDeleted;

            broadcast(new PapiSyncProgressUpdated($this->userId, 96, "Cleaned up {$permanentlyDeleted} old records"));
        });

        broadcast(new PapiSyncProgressUpdated($this->userId, 98, 'Finalizing sync...'));

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
        Cache::put("last_papi_sync_result_{$this->userId}", $stats, 86400);
        Cache::put("papi_sync_status_{$this->userId}", $stats['status'], 86400);
    }

    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('SyncPapisJob failed after all retries', [
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
        Cache::forget("papi_sync_lock_" . $this->userId);

        // Kirim event failed
        broadcast(new PapiSyncStatusUpdated(
            $this->userId,
            'failed',
            ['error' => $exception->getMessage()],
            'PAPI sync failed: ' . $exception->getMessage()
        ));
    }
}
