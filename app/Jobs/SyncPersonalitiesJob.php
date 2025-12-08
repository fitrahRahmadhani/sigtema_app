<?php

namespace App\Jobs;

use App\Models\Employee;
use App\Models\Personality;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncPersonalitiesJob implements ShouldQueue
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
        Log::info('Starting personality sync job', ['user_id' => $this->userId]);

        try {
            $apiData = $this->fetchApiData();
            $stats = $this->processData($apiData);

            Log::info('Personality sync job completed', $stats);
            $this->saveSyncResult($stats);
        } catch (\Exception $e) {
            Log::error('Personality sync job failed', [
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

        // TODO: Ganti dengan API sebenarnya
        $response = Http::get('https://api.example.com/personalities');

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

                $personality = Personality::updateOrCreate(
                    [
                        'source' => 'eksternal',
                        'external_id' => $data['id'],
                    ],
                    [
                        'employee_id' => $employee?->id,
                        'employee_number' => $data['employee_number'],
                        'mbti' => $data['mbti'] ?? null,
                        'disc' => $data['disc'] ?? null,
                        'papi' => $data['papi'] ?? null,
                        'updated_at' => now()
                    ]
                );

                $personality->wasRecentlyCreated ? $stats['created']++ : $stats['updated']++;
            } catch (\Exception $e) {
                $stats['skipped']++;
                Log::warning('Failed to process personality', [
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
        $cacheKey = "personality_sync_result_{$this->userId}_" . now()->format('YmdHis');
        Cache::put($cacheKey, $stats, 3600);
        Cache::put("last_personality_sync_result_{$this->userId}", $stats, 3600);
    }

    private function getMockData(): array
    {
        return [
            [
                'id' => 'PERSONALITY-2024-001',
                'employee_number' => '00007106',
                'mbti' => 'ENTJ',
                'disc' => 'DI',
                'papi' => 'Dominant'
            ],
            [
                'id' => 'PERSONALITY-2024-002',
                'employee_number' => '00007107',
                'mbti' => 'ISFJ',
                'disc' => 'SC',
                'papi' => 'Supportive'
            ],
            [
                'id' => 'PERSONALITY-2024-003',
                'employee_number' => '00007108',
                'mbti' => 'ENFP',
                'disc' => 'ID',
                'papi' => 'Influential'
            ]
        ];
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('SyncPersonalitiesJob failed after all retries', [
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
