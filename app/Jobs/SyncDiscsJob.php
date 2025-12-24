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

            // Fetch data dari API dengan pagination cursor
            broadcast(new DiscSyncProgressUpdated($this->userId, 5, 'Starting API data fetch...'));

            // Kumpulkan semua data dari API dengan cursor pagination
            $allApiData = $this->fetchAllApiData();

            if (empty($allApiData)) {
                throw new \Exception('No DISC data received from API');
            }

            // Kirim event progress
            broadcast(new DiscSyncProgressUpdated($this->userId, 30, 'Processing ' . count($allApiData) . ' DISC assessments...', 0, count($allApiData)));

            // Process data
            $stats = $this->processData($allApiData);

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
     * Fetch semua data dari API dengan cursor pagination
     */
    private function fetchAllApiData(): array
    {
        $apiUrl = config('services.disc_api.url');
        $bearerToken = config('services.disc_api.token');

        if (!$apiUrl || !$bearerToken) {
            throw new \Exception('DISC API configuration not found');
        }

        Log::info('Fetching DISC data from API', ['url' => $apiUrl]);

        $allData = [];
        $nextCursor = null;
        $page = 1;

        do {
            broadcast(new DiscSyncProgressUpdated(
                $this->userId,
                5 + (($page - 1) * 5),
                "Fetching page {$page} from DISC API..."
            ));

            $responseData = $this->fetchApiPage($apiUrl, $bearerToken, $nextCursor);

            if (!empty($responseData['data'])) {
                $allData = array_merge($allData, $responseData['data']);
                $nextCursor = $responseData['meta']['next_cursor'] ?? null;

                Log::info('Fetched page from DISC API', [
                    'page' => $page,
                    'items_fetched' => count($responseData['data']),
                    'total_so_far' => count($allData),
                    'has_next' => !empty($nextCursor)
                ]);
            } else {
                $nextCursor = null;
            }

            $page++;

            // Safety limit: maksimal 1000 halaman
            if ($page > 1000) {
                Log::warning('Reached maximum page limit for DISC sync', ['total_items' => count($allData)]);
                break;
            }
        } while (!empty($nextCursor));

        broadcast(new DiscSyncProgressUpdated($this->userId, 25, 'Validating DISC data...'));

        // Validasi data
        $validatedData = $this->validateApiData($allData);

        broadcast(new DiscSyncProgressUpdated(
            $this->userId,
            30,
            'Validated ' . count($validatedData) . ' DISC assessments from ' . count($allData) . ' total'
        ));

        Log::info('DISC API data validated', [
            'total_fetched' => count($allData),
            'total_valid' => count($validatedData)
        ]);

        return $validatedData;
    }

    /**
     * Fetch satu halaman data dari API
     */
    private function fetchApiPage(string $apiUrl, string $bearerToken, ?string $cursor = null): array
    {
        $queryParams = [];
        if ($cursor) {
            $queryParams['cursor'] = $cursor;
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $bearerToken,
            'Accept' => 'application/json',
        ])->timeout(300)->get($apiUrl, $queryParams);

        if (!$response->successful()) {
            $errorMessage = 'DISC API request failed: ' . $response->status();
            Log::error('DISC API request failed', [
                'status' => $response->status(),
                'url' => $apiUrl,
                'cursor' => $cursor,
                'response' => $response->body()
            ]);

            // Jika error 401, token mungkin expired
            if ($response->status() === 401) {
                throw new \Exception('DISC API authentication failed. Please check the bearer token.');
            }

            throw new \Exception($errorMessage);
        }

        $data = $response->json();

        // Validasi struktur response
        if (!isset($data['data']) || !is_array($data['data'])) {
            throw new \Exception('Invalid DISC API response: missing data array');
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
                Log::warning('Invalid DISC item format at index ' . $index);
                continue;
            }

            // Validasi field required
            if (empty($item['register_id'])) {
                Log::warning('Missing required field register_id at index ' . $index);
                continue;
            }

            if (empty($item['personnel_number'])) {
                Log::warning('Missing required field personnel_number at index ' . $index);
                continue;
            }

            // Format employee_number (personnel_number)
            $employeeNumber = trim($item['personnel_number']);

            // Map field dari API ke struktur database
            $validatedData[] = [
                'external_id' => $item['register_id'],
                'employee_number' => $employeeNumber,
                'D1' => $item['D1'] ?? null,
                'I1' => $item['I1'] ?? null,
                'S1' => $item['S1'] ?? null,
                'C1' => $item['C1'] ?? null,
                'D2' => $item['D2'] ?? null,
                'I2' => $item['I2'] ?? null,
                'S2' => $item['S2'] ?? null,
                'C2' => $item['C2'] ?? null,
                'D3' => $item['D3'] ?? null,
                'I3' => $item['I3'] ?? null,
                'S3' => $item['S3'] ?? null,
                'C3' => $item['C3'] ?? null,
                'interpretation1' => $item['interpretation_1'] ?? null,
                'interpretation2' => $item['interpretation_2'] ?? null,
                'interpretation3' => $item['interpretation_3'] ?? null,
                'total_p_raw' => $item['total_p_raw'] ?? null,
                'total_k_raw' => $item['total_k_raw'] ?? null,
                'test_date' => $item['test_date (yyyy-mm-dd)'] ?? null,
                'duration' => $item['duration'] ?? null,
                'api_data' => $item // Simpan data lengkap untuk debugging
            ];
        }

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

            // 2. Process data dari API
            $totalItems = count($apiData);
            $processed = 0;

            foreach ($apiData as $item) {
                try {
                    $employeeNumber = $item['employee_number'];
                    $externalId = $item['external_id'];

                    // Cari employee
                    $employee = Employee::where('employee_number', $employeeNumber)->first();

                    // Update atau create DISC menggunakan external_id sebagai unique key
                    $disc = Disc::updateOrCreate(
                        [
                            'source' => 'eksternal',
                            'external_id' => $externalId,
                        ],
                        [
                            'employee_id' => $employee?->id,
                            'employee_number' => $employeeNumber,
                            'D1' => $item['D1'] ?? null,
                            'I1' => $item['I1'] ?? null,
                            'S1' => $item['S1'] ?? null,
                            'C1' => $item['C1'] ?? null,
                            'D2' => $item['D2'] ?? null,
                            'I2' => $item['I2'] ?? null,
                            'S2' => $item['S2'] ?? null,
                            'C2' => $item['C2'] ?? null,
                            'D3' => $item['D3'] ?? null,
                            'I3' => $item['I3'] ?? null,
                            'S3' => $item['S3'] ?? null,
                            'C3' => $item['C3'] ?? null,
                            'interpretation1' => $item['interpretation1'] ?? null,
                            'interpretation2' => $item['interpretation2'] ?? null,
                            'interpretation3' => $item['interpretation3'] ?? null,
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
                        'employee_number' => $item['employee_number'] ?? 'unknown',
                        'external_id' => $item['external_id'] ?? 'unknown',
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
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
