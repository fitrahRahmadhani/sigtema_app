<?php

namespace App\Jobs;

use App\Models\Employee;
use App\Models\Disc;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncDiscsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userId;
    public $tries = 3;
    public $timeout = 300;

    public function __construct($userId = null)
    {
        $this->userId = $userId;
    }

    public function handle()
    {
        Log::info('Starting DISC sync job', ['user_id' => $this->userId]);

        try {
            $apiData = $this->fetchApiData();
            $stats = $this->processData($apiData);

            Log::info('DISC sync job completed', $stats);
            $this->saveSyncResult($stats);
        } catch (\Exception $e) {
            Log::error('DISC sync job failed', [
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

    private function fetchApiData(): array
    {
        if (app()->environment('local', 'testing')) {
            return $this->getMockData();
        }

        $response = Http::get('https://api.example.com/discs');

        if (!$response->successful()) {
            throw new \Exception('API request failed: ' . $response->status());
        }

        return $response->json();
    }

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
                if (empty($data['id']) || !isset($data['employee_number'])) {
                    $stats['skipped']++;
                    continue;
                }

                $employee = Employee::where('employee_number', $data['employee_number'])->first();

                $disc = Disc::updateOrCreate(
                    [
                        'source' => 'eksternal',
                        'external_id' => $data['id'],
                    ],
                    [
                        'employee_id' => $employee?->id,
                        'employee_number' => $data['employee_number'],
                        'D1' => $data['D1'] ?? null,
                        'I1' => $data['I1'] ?? null,
                        'S1' => $data['S1'] ?? null,
                        'C1' => $data['C1'] ?? null,
                        'D2' => $data['D2'] ?? null,
                        'I2' => $data['I2'] ?? null,
                        'S2' => $data['S2'] ?? null,
                        'C2' => $data['C2'] ?? null,
                        'D3' => $data['D3'] ?? null,
                        'I3' => $data['I3'] ?? null,
                        'S3' => $data['S3'] ?? null,
                        'C3' => $data['C3'] ?? null,
                        'interpretation1' => $data['interpretation1'] ?? null,
                        'interpretation2' => $data['interpretation2'] ?? null,
                        'interpretation3' => $data['interpretation3'] ?? null,
                        'updated_at' => now()
                    ]
                );

                $disc->wasRecentlyCreated ? $stats['created']++ : $stats['updated']++;
            } catch (\Exception $e) {
                $stats['skipped']++;
                Log::warning('Failed to process DISC', [
                    'data' => $data,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $stats['completed_at'] = now();
        return $stats;
    }

    private function saveSyncResult(array $stats): void
    {
        $cacheKey = "disc_sync_result_{$this->userId}_" . now()->format('YmdHis');
        Cache::put($cacheKey, $stats, 3600);
        Cache::put("last_disc_sync_result_{$this->userId}", $stats, 3600);
    }

    private function getMockData(): array
    {
        return [
            [
                'id' => 'DISC-2024-001',
                'employee_number' => '00007106',
                'D1' => 8,
                'I1' => 6,
                'S1' => 4,
                'C1' => 2,
                'D2' => 7,
                'I2' => 5,
                'S2' => 3,
                'C2' => 1,
                'D3' => 6,
                'I3' => 4,
                'S3' => 2,
                'C3' => 0,
                'interpretation1' => 'Dominant',
                'interpretation2' => 'Influential',
                'interpretation3' => 'Steady'
            ],
            [
                'id' => 'DISC-2024-002',
                'employee_number' => '00007107',
                'D1' => 2,
                'I1' => 8,
                'S1' => 6,
                'C1' => 4,
                'D2' => 1,
                'I2' => 7,
                'S2' => 5,
                'C2' => 3,
                'D3' => 0,
                'I3' => 6,
                'S3' => 4,
                'C3' => 2,
                'interpretation1' => 'Influential',
                'interpretation2' => 'Steady',
                'interpretation3' => 'Conscientious'
            ]
        ];
    }

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
    }
}
