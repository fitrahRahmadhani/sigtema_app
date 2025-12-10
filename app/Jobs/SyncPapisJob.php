<?php

namespace App\Jobs;

use App\Models\Employee;
use App\Models\Papi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncPapisJob implements ShouldQueue
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
        Log::info('Starting PAPI sync job', ['user_id' => $this->userId]);

        try {
            $apiData = $this->fetchApiData();
            $stats = $this->processData($apiData);

            Log::info('PAPI sync job completed', $stats);
            $this->saveSyncResult($stats);
        } catch (\Exception $e) {
            Log::error('PAPI sync job failed', [
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

        $response = Http::get('https://api.example.com/papis');

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

                $papi = Papi::updateOrCreate(
                    [
                        'source' => 'eksternal',
                        'external_id' => $data['id'],
                    ],
                    [
                        'employee_id' => $employee?->id,
                        'employee_number' => $data['employee_number'],
                        'N' => $data['N'] ?? null,
                        'G' => $data['G'] ?? null,
                        'A' => $data['A'] ?? null,
                        'L' => $data['L'] ?? null,
                        'P' => $data['P'] ?? null,
                        'I' => $data['I'] ?? null,
                        'T' => $data['T'] ?? null,
                        'V' => $data['V'] ?? null,
                        'S' => $data['S'] ?? null,
                        'B' => $data['B'] ?? null,
                        'O' => $data['O'] ?? null,
                        'X' => $data['X'] ?? null,
                        'C' => $data['C'] ?? null,
                        'D' => $data['D'] ?? null,
                        'R' => $data['R'] ?? null,
                        'Z' => $data['Z'] ?? null,
                        'E' => $data['E'] ?? null,
                        'K' => $data['K'] ?? null,
                        'F' => $data['F'] ?? null,
                        'W' => $data['W'] ?? null,
                        'updated_at' => now()
                    ]
                );

                $papi->wasRecentlyCreated ? $stats['created']++ : $stats['updated']++;
            } catch (\Exception $e) {
                $stats['skipped']++;
                Log::warning('Failed to process PAPI', [
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
        $cacheKey = "papi_sync_result_{$this->userId}_" . now()->format('YmdHis');
        Cache::put($cacheKey, $stats, 3600);
        Cache::put("last_papi_sync_result_{$this->userId}", $stats, 3600);
    }

    private function getMockData(): array
    {
        return [
            [
                'id' => 'PAPI-2024-001',
                'employee_number' => '00007106',
                'N' => 8,
                'G' => 6,
                'A' => 7,
                'L' => 5,
                'P' => 4,
                'I' => 3,
                'T' => 9,
                'V' => 2,
                'S' => 1,
                'B' => 6,
                'O' => 5,
                'X' => 7,
                'C' => 4,
                'D' => 3,
                'R' => 8,
                'Z' => 2,
                'E' => 9,
                'K' => 1,
                'F' => 6,
                'W' => 5
            ],
            [
                'id' => 'PAPI-2024-002',
                'employee_number' => '00007107',
                'N' => 5,
                'G' => 8,
                'A' => 6,
                'L' => 7,
                'P' => 3,
                'I' => 9,
                'T' => 4,
                'V' => 1,
                'S' => 8,
                'B' => 2,
                'O' => 7,
                'X' => 5,
                'C' => 9,
                'D' => 4,
                'R' => 6,
                'Z' => 3,
                'E' => 8,
                'K' => 2,
                'F' => 7,
                'W' => 4
            ]
        ];
    }

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
    }
}
