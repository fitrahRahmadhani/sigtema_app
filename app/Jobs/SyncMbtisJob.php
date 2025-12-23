<?php

namespace App\Jobs;

use App\Models\Employee;
use App\Models\Mbti;
use App\Events\MbtiSyncStatusUpdated;
use App\Events\MbtiSyncProgressUpdated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncMbtisJob implements ShouldQueue
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
        $lockKey = "mbti_sync_lock_" . $this->userId;

        // Cek lock untuk mencegah multiple sync
        if (Cache::has($lockKey)) {
            Log::warning('MBTI sync already in progress', ['user_id' => $this->userId]);
            return;
        }

        // Set lock untuk 1 jam
        Cache::put($lockKey, true, 3600);

        try {
            Log::info('Starting MBTI sync job', ['user_id' => $this->userId]);

            // Kirim event status processing
            $this->broadcastStatus('processing', null, 'Starting MBTI sync process...');

            // Fetch semua data dari API dengan cursor pagination
            $this->broadcastProgress(5, 'Starting API data fetch...');

            $allApiData = $this->fetchAllApiData();

            if (empty($allApiData)) {
                throw new \Exception('No data received from API');
            }

            // Kirim event progress
            $this->broadcastProgress(30, 'Processing ' . count($allApiData) . ' items...', 0, count($allApiData));

            // Process data
            $stats = $this->processData($allApiData);

            // PENTING: Kirim progress 100% sebelum status completed
            $this->broadcastProgress(100, 'Sync completed successfully!', $stats['total'], $stats['total']);

            // Delay kecil untuk memastikan event terkirim
            usleep(200000); // 200ms

            Log::info('MBTI sync job completed', $stats);

            // Kirim event completed
            $this->broadcastStatus('completed', $stats, 'MBTI sync completed successfully!');

            $this->saveSyncResult($stats);
        } catch (\Exception $e) {
            Log::error('MBTI sync job failed', [
                'user_id' => $this->userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Kirim event failed
            $this->broadcastStatus('failed', ['error' => $e->getMessage()], 'MBTI sync failed: ' . $e->getMessage());

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
     * Helper untuk broadcast status
     */
    private function broadcastStatus(string $status, ?array $result, string $message): void
    {
        broadcast(new MbtiSyncStatusUpdated($this->userId, $status, $result, $message));

        // Update cache
        Cache::put("mbti_sync_status_{$this->userId}", $status, 86400);
        if ($result) {
            Cache::put("last_mbti_sync_result_{$this->userId}", $result, 86400);
        }
    }

    /**
     * Helper untuk broadcast progress
     */
    private function broadcastProgress(int $progress, string $message, int $current = 0, int $total = 0): void
    {
        broadcast(new MbtiSyncProgressUpdated($this->userId, $progress, $message, $current, $total));
    }

    /**
     * Fetch semua data dari API dengan cursor pagination
     */
    private function fetchAllApiData(): array
    {
        $apiUrl = config('services.mbti_api.url');
        $bearerToken = config('services.mbti_api.token');

        if (!$apiUrl || !$bearerToken) {
            throw new \Exception('MBTI API configuration not found');
        }

        Log::info('Fetching MBTI data from API', ['url' => $apiUrl]);

        $allData = [];
        $nextCursor = null;
        $page = 1;
        $limit = 100;

        do {
            $this->broadcastProgress(
                5 + (($page - 1) * 5),
                "Fetching page {$page} from API..."
            );

            $responseData = $this->fetchApiPage($apiUrl, $bearerToken, $limit, $nextCursor);

            if (!empty($responseData['data'])) {
                $allData = array_merge($allData, $responseData['data']);
                $nextCursor = $responseData['meta']['next_cursor'] ?? null;

                Log::info('Fetched page from API', [
                    'page' => $page,
                    'items_fetched' => count($responseData['data']),
                    'total_so_far' => count($allData),
                    'has_next' => !empty($nextCursor)
                ]);
            } else {
                $nextCursor = null;
            }

            $page++;

            if ($page > 1000) {
                Log::warning('Reached maximum page limit', ['total_items' => count($allData)]);
                break;
            }
        } while (!empty($nextCursor));

        $this->broadcastProgress(25, 'Validating data...');

        $validatedData = $this->validateApiData($allData);

        $this->broadcastProgress(
            30,
            'Validated ' . count($validatedData) . ' items from ' . count($allData) . ' total'
        );

        Log::info('MBTI API data validated', [
            'total_fetched' => count($allData),
            'total_valid' => count($validatedData)
        ]);

        return $validatedData;
    }

    /**
     * Fetch satu halaman data dari API
     */
    private function fetchApiPage(string $apiUrl, string $bearerToken, int $limit = 100, ?string $cursor = null): array
    {
        $queryParams = ['limit' => $limit];
        if ($cursor) {
            $queryParams['cursor'] = $cursor;
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $bearerToken,
            'Accept' => 'application/json',
        ])->timeout(300)->get($apiUrl, $queryParams);

        if (!$response->successful()) {
            $errorMessage = 'API request failed: ' . $response->status();
            Log::error('MBTI API request failed', [
                'status' => $response->status(),
                'url' => $apiUrl,
                'cursor' => $cursor,
                'response' => $response->body()
            ]);

            if ($response->status() === 401) {
                throw new \Exception('API authentication failed. Please check the bearer token.');
            }

            throw new \Exception($errorMessage);
        }

        $data = $response->json();

        if (!isset($data['data']) || !is_array($data['data'])) {
            throw new \Exception('Invalid API response: missing data array');
        }

        return [
            'data' => $data['data'],
            'meta' => $data['meta'] ?? []
        ];
    }

    /**
     * Validasi data dari API
     */
    private function validateApiData(array $apiData): array
    {
        $validatedData = [];

        foreach ($apiData as $index => $item) {
            if (!is_array($item)) {
                Log::warning('Invalid item format at index ' . $index);
                continue;
            }

            if (empty($item['personnel_number'])) {
                Log::warning('Missing required field personnel_number at index ' . $index);
                continue;
            }

            if (empty($item['mbti_type'])) {
                Log::warning('Missing required field mbti_type at index ' . $index);
                continue;
            }

            $employeeNumber = trim($item['personnel_number']);

            if (!preg_match('/^\d{8}$/', $employeeNumber)) {
                Log::warning('Invalid employee number format: ' . $employeeNumber);
                continue;
            }

            $mbtiType = strtoupper(trim($item['mbti_type']));
            if (!preg_match('/^[EI][SN][TF][JP]$/', $mbtiType)) {
                Log::warning('Invalid MBTI format: ' . $mbtiType . ' at index ' . $index);
                continue;
            }

            $testDate = $item['test_date'] ?? $item['test_date (yyyy-mm-dd)'] ?? null;
            $externalId = $this->generateExternalId($employeeNumber, $testDate, $mbtiType);

            $validatedData[] = [
                'external_id' => $externalId,
                'employee_number' => $employeeNumber,
                'mbti' => $mbtiType,
                'test_date' => $testDate,
                'api_data' => $item
            ];
        }

        return $validatedData;
    }

    /**
     * Generate external_id unik
     */
    private function generateExternalId(string $employeeNumber, ?string $testDate, string $mbtiType): string
    {
        if ($testDate) {
            return md5($employeeNumber . $testDate . $mbtiType);
        }

        return md5($employeeNumber . $mbtiType);
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
            // 1. Reset semua data external menjadi tidak aktif
            $this->broadcastProgress(35, 'Deactivating existing external MBTI data...');

            $deactivatedCount = Mbti::where('source', 'eksternal')
                ->where('is_active', true)
                ->update(['is_active' => false]);

            $stats['deactivated'] = $deactivatedCount;

            $this->broadcastProgress(40, "Deactivated {$deactivatedCount} MBTI records");

            // 2. Process data dari API
            $totalItems = count($apiData);
            $processed = 0;

            foreach ($apiData as $item) {
                try {
                    $employeeNumber = $item['employee_number'];
                    $externalId = $item['external_id'];

                    $employee = Employee::where('employee_number', $employeeNumber)->first();

                    $mbti = Mbti::updateOrCreate(
                        [
                            'source' => 'eksternal',
                            'external_id' => $externalId,
                        ],
                        [
                            'employee_id' => $employee?->id,
                            'employee_number' => $employeeNumber,
                            'mbti' => $item['mbti'],
                            'is_active' => true,
                            'updated_at' => now()
                        ]
                    );

                    $mbti->wasRecentlyCreated ? $stats['created']++ : $stats['updated']++;
                    $processed++;

                    // Update progress - range 40-90%
                    if ($processed % 10 === 0 || $processed === $totalItems) {
                        $progress = 40 + (($processed / $totalItems) * 50);
                        $this->broadcastProgress(
                            (int) $progress,
                            "Processed {$processed} of {$totalItems} MBTI records",
                            $processed,
                            $totalItems
                        );
                    }
                } catch (\Exception $e) {
                    $stats['skipped']++;
                    Log::warning('Failed to process MBTI', [
                        'employee_number' => $item['employee_number'] ?? 'unknown',
                        'external_id' => $item['external_id'] ?? 'unknown',
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // 3. Hapus data yang sudah tidak aktif dalam waktu lama
            $this->broadcastProgress(92, 'Cleaning up old inactive data...');

            $thirtyDaysAgo = now()->subDays(30);
            $permanentlyDeleted = Mbti::where('source', 'eksternal')
                ->where('is_active', false)
                ->where('updated_at', '<', $thirtyDaysAgo)
                ->delete();

            $stats['permanently_deleted'] = $permanentlyDeleted;

            $this->broadcastProgress(95, "Cleaned up {$permanentlyDeleted} old records");
        });

        $stats['completed_at'] = now();
        $stats['status'] = 'completed';

        return $stats;
    }

    /**
     * Simpan hasil sync
     */
    private function saveSyncResult(array $stats): void
    {
        Cache::put("last_mbti_sync_result_{$this->userId}", $stats, 86400);
        Cache::put("mbti_sync_status_{$this->userId}", $stats['status'], 86400);
    }

    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('SyncMbtisJob failed after all retries', [
            'user_id' => $this->userId,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);

        $this->saveSyncResult([
            'status' => 'failed',
            'error' => $exception->getMessage(),
            'completed_at' => now()
        ]);

        Cache::forget("mbti_sync_lock_" . $this->userId);

        $this->broadcastStatus(
            'failed',
            ['error' => $exception->getMessage()],
            'MBTI sync failed: ' . $exception->getMessage()
        );
    }
}
