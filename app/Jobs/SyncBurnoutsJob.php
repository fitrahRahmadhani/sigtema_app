<?php

namespace App\Jobs;

use App\Models\Employee;
use App\Models\Burnout;
use App\Events\BurnoutSyncStatusUpdated;
use App\Events\BurnoutSyncProgressUpdated;
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

class SyncBurnoutsJob implements ShouldQueue
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
        $lockKey = "burnout_sync_lock_" . $this->userId;

        // Cek lock untuk mencegah multiple sync
        if (Cache::has($lockKey)) {
            Log::warning('Burnout sync already in progress', ['user_id' => $this->userId]);
            return;
        }

        // Set lock untuk 1 jam
        Cache::put($lockKey, true, 3600);

        try {
            Log::info('Starting burnout sync job', ['user_id' => $this->userId]);

            // Kirim event status processing
            broadcast(new BurnoutSyncStatusUpdated($this->userId, 'processing', null, 'Starting burnout sync process...'));

            // Fetch data dari API
            broadcast(new BurnoutSyncProgressUpdated($this->userId, 10, 'Fetching data from API...'));
            $apiData = $this->fetchApiData();

            if (empty($apiData)) {
                throw new \Exception('No data received from API');
            }

            // Kirim event progress
            broadcast(new BurnoutSyncProgressUpdated($this->userId, 30, 'Processing ' . count($apiData) . ' items...', 0, count($apiData)));

            // Process data
            $stats = $this->processData($apiData);

            Log::info('Burnout sync job completed', $stats);

            // Kirim event completed
            broadcast(new BurnoutSyncStatusUpdated($this->userId, 'completed', $stats, 'Burnout sync completed successfully!'));

            $this->saveSyncResult($stats);
        } catch (\Exception $e) {
            Log::error('Burnout sync job failed', [
                'user_id' => $this->userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Kirim event failed
            broadcast(new BurnoutSyncStatusUpdated($this->userId, 'failed', ['error' => $e->getMessage()], 'Burnout sync failed: ' . $e->getMessage()));

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
        $apiUrl = config('services.burnout_api.url');
        $apiKey = config('services.burnout_api.key');

        if (!$apiUrl || !$apiKey) {
            throw new \Exception('Burnout API configuration not found');
        }

        Log::info('Fetching burnout data from API', ['url' => $apiUrl]);

        broadcast(new BurnoutSyncProgressUpdated($this->userId, 15, 'Connecting to API...'));

        $response = Http::withHeaders([
            'x-api-key' => $apiKey,
            'Accept' => 'application/json',
        ])->timeout(300)->get($apiUrl);

        broadcast(new BurnoutSyncProgressUpdated($this->userId, 20, 'Receiving data from API...'));

        if (!$response->successful()) {
            $errorMessage = 'API request failed: ' . $response->status();
            Log::error('Burnout API request failed', [
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
            Log::warning('Empty data received from burnout API');
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

        broadcast(new BurnoutSyncProgressUpdated($this->userId, 25, 'Validated ' . count($validatedData) . ' items'));
        Log::info('Burnout API data validated', ['total' => count($validatedData)]);

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
            broadcast(new BurnoutSyncProgressUpdated($this->userId, 35, 'Deactivating existing external burnouts...'));

            $deactivatedCount = Burnout::where('source', 'eksternal')
                ->where('is_active', true)
                ->update(['is_active' => false]);

            $stats['deactivated'] = $deactivatedCount;

            broadcast(new BurnoutSyncProgressUpdated($this->userId, 40, "Deactivated {$deactivatedCount} burnouts"));

            // 2. Group data by unique key untuk menghindari duplikasi
            $groupedData = collect($apiData)->groupBy(function ($item) {
                return $this->padEmployeeId($item['employee_number']) . '|' .
                    ($item['result'] ?? '');
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
                    $uniqueIdentifier = md5($employeeNumber . ($data['result'] ?? '') . ($data['assessment_date'] ?? ''));

                    // Update atau create burnout
                    $burnout = Burnout::updateOrCreate(
                        [
                            'source' => 'eksternal',
                            'external_id' => $uniqueIdentifier,
                        ],
                        [
                            'employee_id' => $employee?->id,
                            'employee_number' => $employeeNumber,
                            'emotional_exhaustion' => $data['emotional_exhaustion'] ?? null,
                            'depersonalization' => $data['depersonalization'] ?? null,
                            'achievement_decline' => $data['achievement_decline'] ?? null,
                            'result' => $data['result'] ?? null,
                            'is_active' => true,
                            'updated_at' => now()
                        ]
                    );

                    $burnout->wasRecentlyCreated ? $stats['created']++ : $stats['updated']++;
                    $processed++;

                    // Update progress setiap 10 item atau 10% dari total
                    if ($processed % 10 === 0 || $processed === $totalGroups) {
                        $progress = 40 + (($processed / $totalGroups) * 50);
                        broadcast(new BurnoutSyncProgressUpdated(
                            $this->userId,
                            (int) $progress,
                            "Processed {$processed} of {$totalGroups} burnouts",
                            $processed,
                            $totalGroups
                        ));
                    }
                } catch (\Exception $e) {
                    $stats['skipped']++;
                    Log::warning('Failed to process burnout', [
                        'unique_key' => $uniqueKey,
                        'error' => $e->getMessage(),
                        'data' => $data ?? []
                    ]);
                }
            }

            // 4. Hapus data yang sudah tidak aktif dalam waktu lama
            broadcast(new BurnoutSyncProgressUpdated($this->userId, 92, 'Cleaning up old inactive data...'));

            $thirtyDaysAgo = now()->subDays(30);
            $permanentlyDeleted = Burnout::where('source', 'eksternal')
                ->where('is_active', false)
                ->where('updated_at', '<', $thirtyDaysAgo)
                ->delete();

            $stats['permanently_deleted'] = $permanentlyDeleted;

            broadcast(new BurnoutSyncProgressUpdated($this->userId, 96, "Cleaned up {$permanentlyDeleted} old records"));
        });

        broadcast(new BurnoutSyncProgressUpdated($this->userId, 98, 'Finalizing sync...'));

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
        Cache::put("last_burnout_sync_result_{$this->userId}", $stats, 86400);
        Cache::put("burnout_sync_status_{$this->userId}", $stats['status'], 86400);
    }

    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('SyncBurnoutsJob failed after all retries', [
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
        Cache::forget("burnout_sync_lock_" . $this->userId);

        // Kirim event failed
        broadcast(new BurnoutSyncStatusUpdated(
            $this->userId,
            'failed',
            ['error' => $exception->getMessage()],
            'Burnout sync failed: ' . $exception->getMessage()
        ));
    }
}
