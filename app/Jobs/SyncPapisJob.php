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
            // Kirim event status processing
            broadcast(new PapiSyncStatusUpdated($this->userId, 'processing', null, 'Starting PAPI sync process...'));

            // Fetch data dari API dengan pagination cursor
            broadcast(new PapiSyncProgressUpdated($this->userId, 5, 'Starting API data fetch...'));

            // Kumpulkan semua data dari API dengan cursor pagination
            $allApiData = $this->fetchAllApiData();

            if (empty($allApiData)) {
                throw new \Exception('No data received from API');
            }

            // Kirim event progress
            broadcast(new PapiSyncProgressUpdated($this->userId, 30, 'Processing ' . count($allApiData) . ' items...', 0, count($allApiData)));

            // Process data
            $stats = $this->processData($allApiData);

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
     * Fetch semua data dari API dengan cursor pagination
     */
    private function fetchAllApiData(): array
    {
        $apiUrl = config('services.papi_api.url');
        $bearerToken = config('services.papi_api.token');

        if (!$apiUrl || !$bearerToken) {
            throw new \Exception('PAPI API configuration not found');
        }

        $allData = [];
        $nextCursor = null;
        $page = 1;
        $limit = 100; // Ambil 100 data per request

        do {
            broadcast(new PapiSyncProgressUpdated(
                $this->userId,
                5 + (($page - 1) * 5),
                "Fetching page {$page} from API..."
            ));

            $responseData = $this->fetchApiPage($apiUrl, $bearerToken, $nextCursor);

            if (!empty($responseData['data'])) {
                $allData = array_merge($allData, $responseData['data']);
                $nextCursor = $responseData['meta']['next_cursor'] ?? null;
            } else {
                $nextCursor = null;
            }

            $page++;

            // Safety limit: maksimal 1000 halaman (100,000 data)
            if ($page > 1000) {
                Log::warning('Reached maximum page limit', ['total_items' => count($allData)]);
                break;
            }
        } while (!empty($nextCursor));

        broadcast(new PapiSyncProgressUpdated($this->userId, 25, 'Validating data...'));

        // Validasi data
        $validatedData = $this->validateApiData($allData);

        broadcast(new PapiSyncProgressUpdated(
            $this->userId,
            30,
            'Validated ' . count($validatedData) . ' items from ' . count($allData) . ' total'
        ));

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
            $errorMessage = 'API request failed: ' . $response->status();
            Log::error('PAPI API request failed', [
                'status' => $response->status(),
                'url' => $apiUrl,
                'cursor' => $cursor,
                'response' => $response->body()
            ]);

            // Jika error 401, token mungkin expired
            if ($response->status() === 401) {
                throw new \Exception('API authentication failed. Please check the bearer token.');
            }

            throw new \Exception($errorMessage);
        }

        $data = $response->json();

        // Validasi struktur response
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
                'G' => $item['G'] ?? null,
                'L' => $item['L'] ?? null,
                'I' => $item['I'] ?? null,
                'T' => $item['T'] ?? null,
                'V' => $item['V'] ?? null,
                'S' => $item['S'] ?? null,
                'R' => $item['R'] ?? null,
                'D' => $item['D'] ?? null,
                'C' => $item['C'] ?? null,
                'E' => $item['E'] ?? null,
                'W' => $item['W'] ?? null,
                'F' => $item['F'] ?? null,
                'K' => $item['K'] ?? null,
                'Z' => $item['Z'] ?? null,
                'O' => $item['O'] ?? null,
                'B' => $item['B'] ?? null,
                'X' => $item['X'] ?? null,
                'P' => $item['P'] ?? null,
                'A' => $item['A'] ?? null,
                'N' => $item['N'] ?? null,
                'api_data' => $item // Simpan data lengkap untuk debugging
            ];
        }

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

            // 2. Process data dari API
            $totalItems = count($apiData);
            $processed = 0;

            foreach ($apiData as $item) {
                try {
                    $employeeNumber = $item['employee_number'];
                    $externalId = $item['external_id'];

                    // Cari employee
                    $employee = Employee::where('employee_number', $employeeNumber)->first();

                    // Update atau create PAPI menggunakan external_id sebagai unique key
                    $papi = Papi::updateOrCreate(
                        [
                            'source' => 'eksternal',
                            'external_id' => $externalId,
                        ],
                        [
                            'employee_id' => $employee?->id,
                            'employee_number' => $employeeNumber,
                            'N' => $item['N'] ?? null,
                            'G' => $item['G'] ?? null,
                            'A' => $item['A'] ?? null,
                            'L' => $item['L'] ?? null,
                            'P' => $item['P'] ?? null,
                            'I' => $item['I'] ?? null,
                            'T' => $item['T'] ?? null,
                            'V' => $item['V'] ?? null,
                            'S' => $item['S'] ?? null,
                            'B' => $item['B'] ?? null,
                            'O' => $item['O'] ?? null,
                            'X' => $item['X'] ?? null,
                            'C' => $item['C'] ?? null,
                            'D' => $item['D'] ?? null,
                            'R' => $item['R'] ?? null,
                            'Z' => $item['Z'] ?? null,
                            'E' => $item['E'] ?? null,
                            'K' => $item['K'] ?? null,
                            'F' => $item['F'] ?? null,
                            'W' => $item['W'] ?? null,
                            'is_active' => true,
                            'updated_at' => now()
                        ]
                    );

                    $papi->wasRecentlyCreated ? $stats['created']++ : $stats['updated']++;
                    $processed++;

                    // Update progress setiap 10 item atau 10% dari total
                    if ($processed % 10 === 0 || $processed === $totalItems) {
                        $progress = 40 + (($processed / $totalItems) * 50);
                        broadcast(new PapiSyncProgressUpdated(
                            $this->userId,
                            (int) $progress,
                            "Processed {$processed} of {$totalItems} PAPI records",
                            $processed,
                            $totalItems
                        ));
                    }
                } catch (\Exception $e) {
                    $stats['skipped']++;
                    Log::warning('Failed to process PAPI', [
                        'employee_number' => $item['employee_number'] ?? 'unknown',
                        'external_id' => $item['external_id'] ?? 'unknown',
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            // 3. Hapus data yang sudah tidak aktif dalam waktu lama
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
