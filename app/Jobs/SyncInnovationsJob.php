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

class SyncInnovationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userId;
    public $tries = 3;
    public $timeout = 600;

    // Tambahkan ini agar job berjalan di background meskipun user close page
    public $deleteWhenMissingModels = true;

    public function __construct($userId = null)
    {
        $this->userId = $userId;
    }

    public function handle()
    {
        Log::info('Starting innovation sync job', ['user_id' => $this->userId]);

        // Set status processing
        Cache::put("sync_status_{$this->userId}", 'processing', 3600);

        try {
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
        }
    }

    /**
     * Fetch data dari API dengan authentication
     */
    private function fetchApiData(): array
    {
        $apiUrl = config('services.innovation_api.url');
        $apiKey = config('services.innovation_api.key');

        $response = Http::withHeaders([
            'x-api-key' => $apiKey,
            'Accept' => 'application/json',
        ])->timeout(120)->get($apiUrl);

        if (!$response->successful()) {
            $errorMessage = 'API request failed: ' . $response->status();

            // Log detail error untuk debugging
            Log::error('API request failed', [
                'status' => $response->status(),
                'url' => $apiUrl,
                'response' => $response->body(),
                'headers' => $response->headers()
            ]);

            throw new \Exception($errorMessage);
        }

        $data = $response->json();

        // Ambil data dari key 'data' jika ada
        return $data['data'] ?? $data;
    }

    /**
     * Process data dengan strategi upsert untuk menghindari duplikasi
     */
    private function processData(array $apiData): array
    {
        $stats = [
            'total' => count($apiData),
            'created' => 0,
            'updated' => 0,
            'skipped' => 0,
            'started_at' => now(),
            'completed_at' => null,
            'status' => 'completed'
        ];

        // Group data by unique key untuk menghindari duplikasi
        $groupedData = collect($apiData)->groupBy(function ($item) {
            // Buat unique key dari kombinasi employee_id + judul + event
            return $this->padEmployeeId($item['employee_id']) . '|' .
                trim($item['judul_inovasi']) . '|' .
                ($item['event'] ?? '');
        });

        DB::transaction(function () use ($groupedData, &$stats) {
            foreach ($groupedData as $uniqueKey => $items) {
                try {
                    // Ambil item pertama dari group (untuk menghindari duplikasi)
                    $data = $items->first();

                    // Validasi data wajib
                    if (empty($data['employee_id']) || empty($data['judul_inovasi'])) {
                        $stats['skipped']++;
                        continue;
                    }

                    // Format employee_id menjadi 8 digit dengan leading zero
                    $employeeNumber = $this->padEmployeeId($data['employee_id']);

                    // Cari employee berdasarkan employee_number
                    $employee = Employee::where('employee_number', $employeeNumber)->first();

                    // Buat unique identifier untuk innovation
                    $uniqueIdentifier = md5($employeeNumber . $data['judul_inovasi'] . ($data['event'] ?? ''));

                    // Update atau create innovation dengan unique constraint
                    $innovation = Innovation::updateOrCreate(
                        [
                            'source' => 'eksternal',
                            'external_id' => $uniqueIdentifier, // Gunakan hash sebagai ID unik
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
                            'updated_at' => now()
                        ]
                    );

                    $innovation->wasRecentlyCreated ? $stats['created']++ : $stats['updated']++;
                } catch (\Exception $e) {
                    $stats['skipped']++;
                    Log::warning('Failed to process innovation', [
                        'unique_key' => $uniqueKey,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        });

        $stats['completed_at'] = now();
        return $stats;
    }

    /**
     * Format employee_id menjadi 8 digit dengan leading zero
     */
    private function padEmployeeId($employeeId): string
    {
        return str_pad((string)$employeeId, 8, '0', STR_PAD_LEFT);
    }

    /**
     * Simpan hasil sync untuk diakses oleh user
     */
    private function saveSyncResult(array $stats): void
    {
        // Simpan sebagai last result
        Cache::put("last_sync_result_{$this->userId}", $stats, 86400); // 24 jam

        // Update status
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
    }
}
