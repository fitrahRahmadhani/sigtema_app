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

    // Simpan user ID untuk notifikasi
    public $userId;

    // Tambahkan retry logic sederhana
    public $tries = 3;

    // Timeout setelah 10 menit
    public $timeout = 600;

    public function __construct($userId = null)
    {
        $this->userId = $userId;
    }

    public function handle()
    {
        Log::info('Starting innovation sync job', ['user_id' => $this->userId]);

        try {
            // Fetch data dari API
            $apiData = $this->fetchApiData();

            // Process data
            $stats = $this->processData($apiData);

            Log::info('Sync job completed', $stats);

            // Simpan hasil untuk ditampilkan ke user
            $this->saveSyncResult($stats);
        } catch (\Exception $e) {
            Log::error('Sync job failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Simpan error result
            $this->saveSyncResult([
                'status' => 'failed',
                'error' => $e->getMessage(),
                'completed_at' => now()
            ]);

            throw $e; // Biarkan queue handle retry
        }
    }

    /**
     * Fetch data dari API
     */
    private function fetchApiData(): array
    {
        // Untuk development, gunakan mock data
        if (app()->environment('local', 'testing')) {
            return $this->getMockData();
        }

        // TODO: Ganti dengan API sebenarnya
        $response = Http::get('https://api.example.com/innovations');

        if (!$response->successful()) {
            throw new \Exception('API request failed: ' . $response->status());
        }

        return $response->json();
    }

    /**
     * Process data ke database
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

        foreach ($apiData as $data) {
            try {
                // Skip jika data tidak valid
                if (empty($data['id']) || empty($data['judul'])) {
                    $stats['skipped']++;
                    continue;
                }

                // Cari employee
                $employee = null;
                if (!empty($data['no_karyawan'])) {
                    $employee = Employee::where('employee_number', $data['no_karyawan'])->first();
                }

                // Update atau create innovation
                $innovation = Innovation::updateOrCreate(
                    [
                        'source' => 'eksternal',
                        'external_id' => $data['id'],
                    ],
                    [
                        'employee_id' => $employee?->id,
                        'employee_number' => $data['no_karyawan'] ?? null,
                        'title' => trim($data['judul']),
                        'team' => $data['team'] ?? null,
                        'theme' => $data['tema'] ?? null,
                        'event' => $data['event'] ?? null,
                        'category' => $data['kategori'] ?? null,
                        'replication' => $data['repliksi'] ?? null,
                        'status' => $data['status'] ?? null,
                        'achievement' => $data['pencapaian'] ?? null,
                        'updated_at' => now()
                    ]
                );

                $innovation->wasRecentlyCreated ? $stats['created']++ : $stats['updated']++;
            } catch (\Exception $e) {
                $stats['skipped']++;
                Log::warning('Failed to process innovation', [
                    'data' => $data,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $stats['completed_at'] = now();

        return $stats;
    }

    /**
     * Simpan hasil sync untuk diakses oleh user
     */
    private function saveSyncResult(array $stats): void
    {
        $cacheKey = "sync_result_{$this->userId}_" . now()->format('YmdHis');

        // Simpan di cache selama 1 jam
        Cache::put($cacheKey, $stats, 3600);

        // Juga simpan sebagai last result
        Cache::put("last_sync_result_{$this->userId}", $stats, 3600);
    }

    /**
     * Mock data untuk development
     */
    private function getMockData(): array
    {
        return [
            [
                'id' => 'INNO-2024-001',
                'no_karyawan' => '00007106',
                'judul' => 'AI-Powered Customer Service Chatbot',
                'team' => 'Digital Innovation',
                'tema' => 'Artificial Intelligence',
                'event' => 'Innovation Award 2024',
                'kategori' => 'Technology',
                'repliksi' => 'bisa direplikasi',
                'status' => 'innovator',
                'pencapaian' => 'Juara 1 Nasional'
            ],
            [
                'id' => 'INNO-2024-002',
                'no_karyawan' => '00007106',
                'judul' => 'Sustainable Office Energy Management',
                'team' => 'Green Initiative',
                'tema' => 'Environment',
                'event' => 'Green Innovation 2024',
                'kategori' => 'Environment',
                'repliksi' => 'sebagian',
                'status' => 'peserta',
                'pencapaian' => 'Finalis'
            ]
        ];
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

        // Simpan status failed
        $this->saveSyncResult([
            'status' => 'failed',
            'error' => $exception->getMessage(),
            'completed_at' => now()
        ]);
    }
}
