<?php

namespace App\Jobs;

use App\Models\Employee;
use App\Models\Innovation;
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

            // Set status processing
            Cache::put("sync_status_{$this->userId}", 'processing', 3600);

            $apiData = $this->fetchApiData();
            $stats = $this->processData($apiData);

            Log::info('Sync job completed', $stats);
            $this->saveSyncResult($stats);

        } catch (\Exception $e) {
            Log::error('Sync job failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

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

        Log::info('Fetching data from API', ['url' => $apiUrl]);

        $response = Http::withHeaders([
            'x-api-key' => $apiKey,
            'Accept' => 'application/json',
        ])->timeout(300)->get($apiUrl); // 5 menit timeout

        if (!$response->successful()) {
            $errorMessage = 'API request failed: ' . $response->status();
            Log::error('API request failed', [
                'status' => $response->status(),
                'url' => $apiUrl,
                'response' => $response->body(),
                'headers' => $response->headers()
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
                    'item' => $item
                ]);
                continue;
            }

            $validatedData[] = $item;
        }

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
            'started_at' => now(),
            'completed_at' => null,
            'status' => 'completed'
        ];

        // Group data by unique key untuk menghindari duplikasi
        $groupedData = collect($apiData)->groupBy(function ($item) {
            return $this->padEmployeeId($item['employee_id']) . '|' .
                trim($item['judul_inovasi']) . '|' .
                ($item['event'] ?? '');
        });

        DB::transaction(function () use ($groupedData, &$stats) {
            // 1. Reset semua data external menjadi tidak aktif terlebih dahulu
            Innovation::where('source', 'eksternal')
                ->update(['is_active' => false]);

            $stats['deactivated'] = Innovation::where('source', 'eksternal')->count();

            // 2. Ekstrak semua external_id dari API
            $apiExternalIds = [];

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
                    $apiExternalIds[] = $uniqueIdentifier;

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
                            'is_active' => true, // Set aktif karena ada di API
                            'updated_at' => now()
                        ]
                    );

                    $innovation->wasRecentlyCreated ? $stats['created']++ : $stats['updated']++;

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
            // Data dengan is_active = false dan updated_at > 30 hari bisa dihapus
            $thirtyDaysAgo = now()->subDays(30);
            $permanentlyDeleted = Innovation::where('source', 'eksternal')
                ->where('is_active', false)
                ->where('updated_at', '<', $thirtyDaysAgo)
                ->delete();

            $stats['permanently_deleted'] = $permanentlyDeleted;
        });

        $stats['completed_at'] = now();
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
    }
}
