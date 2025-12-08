<?php

namespace App\Jobs;

use App\Models\Employee;
use App\Models\Burnout;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncBurnoutsJob implements ShouldQueue
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
        Log::info('Starting burnout sync job', ['user_id' => $this->userId]);

        try {
            $apiData = $this->fetchApiData();
            $stats = $this->processData($apiData);

            Log::info('Burnout sync job completed', $stats);
            $this->saveSyncResult($stats);
        } catch (\Exception $e) {
            Log::error('Burnout sync job failed', [
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

        $response = Http::get('https://api.example.com/burnouts');

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

                $burnout = Burnout::updateOrCreate(
                    [
                        'source' => 'eksternal',
                        'external_id' => $data['id'],
                    ],
                    [
                        'employee_id' => $employee?->id,
                        'employee_number' => $data['employee_number'],
                        'emotional_exhaustion' => $data['emotional_exhaustion'] ?? null,
                        'depersonalization' => $data['depersonalization'] ?? null,
                        'achievement_decline' => $data['achievement_decline'] ?? null,
                        'result' => $data['result'] ?? null,
                        'updated_at' => now()
                    ]
                );

                $burnout->wasRecentlyCreated ? $stats['created']++ : $stats['updated']++;
            } catch (\Exception $e) {
                $stats['skipped']++;
                Log::warning('Failed to process burnout', [
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
        $cacheKey = "burnout_sync_result_{$this->userId}_" . now()->format('YmdHis');
        Cache::put($cacheKey, $stats, 3600);
        Cache::put("last_burnout_sync_result_{$this->userId}", $stats, 3600);
    }

    private function getMockData(): array
    {
        return [
            [
                'id' => 'BURNOUT-2024-001',
                'employee_number' => '00007106',
                'emotional_exhaustion' => 25,
                'depersonalization' => 18,
                'achievement_decline' => 12,
                'result' => 'Low'
            ],
            [
                'id' => 'BURNOUT-2024-002',
                'employee_number' => '00007107',
                'emotional_exhaustion' => 35,
                'depersonalization' => 28,
                'achievement_decline' => 22,
                'result' => 'Moderate'
            ]
        ];
    }

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
    }
}
