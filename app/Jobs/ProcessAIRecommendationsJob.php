<?php

namespace App\Jobs;

use App\Models\Position;
use Illuminate\Bus\Queueable;
use Masmerise\Toaster\Toaster;
use App\Events\AIAnalysisFailed;
use App\Events\AIAnalysisProgress;
use App\Events\AIAnalysisCompleted;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\FileBasedAIRecommendationService;

class ProcessAIRecommendationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $position;
    public $userId;
    public $tries = 1;
    public $timeout = 300;
    public $backoff = [60, 120, 300];

    public function __construct(Position $position, $userId)
    {
        $this->position = $position;
        $this->userId = $userId;
    }

    public function handle(FileBasedAIRecommendationService $service)
    {
        $cacheKey = "ai_analysis_{$this->position->id}";
        $progressKey = "ai_progress_{$this->position->id}";

        try {
            // Broadcast progress
            $this->broadcastProgress(10, "Starting AI analysis...");

            $results = $service->analyzeWithFileUpload($this->position, function ($progress, $message) {
                $this->broadcastProgress($progress, $message);
            });

            // Store results in cache for 24 hours
            Cache::put($cacheKey, $results, now()->addHours(24));
            Cache::forget($progressKey); // Clear progress cache

            // Broadcast completion
            $this->broadcastProgress(100, "Analysis complete!");
            $this->broadcastCompletion($results);

            Log::info("AI Analysis completed successfully for position {$this->position->id}");
        } catch (\Exception $e) {
            Log::error("AI Job Failed: {$e->getMessage()}", [
                'position_id' => $this->position->id,
                'user_id' => $this->userId,
                'trace' => $e->getTraceAsString()
            ]);

            Cache::forget($progressKey);
            $this->broadcastError("AI analysis failed: " . $e->getMessage());
            return;
        }
    }

    private function broadcastProgress($progress, $message)
    {
        // Store progress in cache untuk polling fallback
        $progressKey = "ai_progress_{$this->position->id}";
        Cache::put($progressKey, [
            'progress' => $progress,
            'message' => $message
        ], now()->addMinutes(10));

        // Broadcast via Laravel Echo
        broadcast(new AIAnalysisProgress($this->position->id, $this->userId, $progress, $message))->toOthers();

        Log::info("Progress broadcasted: {$progress}% - {$message}");
    }

    private function broadcastCompletion($results)
    {
        broadcast(new AIAnalysisCompleted($this->position->id, $this->userId, $results))->toOthers();

        Log::info("Completion broadcasted for position {$this->position->id}");
    }

    private function broadcastError($error)
    {
        broadcast(new AIAnalysisFailed($this->position->id, $this->userId, $error))->toOthers();

        Log::error("Error broadcasted: {$error}");
    }

    public function failed(\Throwable $exception)
    {
        $this->broadcastError("Job failed: " . $exception->getMessage());

        Log::critical("Job failed permanently", [
            'position_id' => $this->position->id,
            'user_id' => $this->userId,
            'exception' => $exception->getMessage()
        ]);
    }
}
