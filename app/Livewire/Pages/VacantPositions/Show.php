<?php

namespace App\Livewire\Pages\VacantPositions;

use Livewire\Component;
use App\Models\Position;
use App\Models\Employee;
use App\Jobs\ProcessAIRecommendationsJob;
use Masmerise\Toaster\Toaster;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

#[Layout('components.layouts.app')]
class Show extends Component
{
    #[Title('Candidates Match')]

    public $positionSlug;
    public $selectedCandidates = [];
    public $aiProcessing = false;
    public $aiProgress = 0;
    public $aiStatus = '';
    public $recommendations = [];
    public $position;

    public function mount(Position $position)
    {
        $this->position = $position;

        // Check for cached results
        $cacheKey = "ai_analysis_{$this->position->id}";
        $cachedResults = Cache::get($cacheKey);

        if ($cachedResults) {
            $this->recommendations = $cachedResults;
            $this->aiProcessing = false;
            $this->aiProgress = 100;
            Toaster::info('Loaded cached AI recommendations');
        }
    }
    protected function getListeners()
    {
        $userId = Auth::id();
        $positionId = $this->position->id;

        return [
            "echo-private:ai-analysis.{$userId}.{$positionId},.ai.progress" => 'handleAIProgress',
            "echo-private:ai-analysis.{$userId}.{$positionId},.ai.completed" => 'handleAICompleted',
            "echo-private:ai-analysis.{$userId}.{$positionId},.ai.failed" => 'handleAIFailed',
        ];
    }

    public function startAIProcessing()
    {
        $this->aiProcessing = true;
        $this->aiProgress = 0;
        $this->aiStatus = 'Initializing AI analysis...';

        // Dispatch job
        ProcessAIRecommendationsJob::dispatch($this->position, Auth::id());

        Toaster::info('AI analysis started. This may take 1-2 minutes.');
    }

    public function retryAIProcessing()
    {
        Cache::forget("ai_analysis_{$this->position->id}");
        $this->recommendations = [];
        $this->startAIProcessing();
    }

    public function handleAIProgress($event)
    {
        Log::info('Livewire received progress event', [
            'event' => $event,
            'position_id' => $this->position->id,
            'user_id' => Auth::id()
        ]);

        $this->aiProgress = $event['progress'];
        $this->aiStatus = $event['message'];

        // Update UI
        $this->dispatch('status-updated');
    }

    public function handleAICompleted($event)
    {
        Log::info('Livewire received completed event', [
            'event' => $event,
            'position_id' => $this->position->id
        ]);

        $this->recommendations = $event['results'];
        $this->aiProcessing = false;
        $this->aiProgress = 100;
        $this->aiStatus = 'Analysis complete!';

        // Store in cache
        Cache::put("ai_analysis_{$this->position->id}", $event['results'], now()->addHours(24));

        Toaster::success('AI analysis complete! Top 10 candidates found.');

        // Update UI
        $this->dispatch('status-updated');
    }

    public function handleAIFailed($event)
    {
        Log::info('Livewire received failed event', [
            'event' => $event,
            'position_id' => $this->position->id
        ]);

        $this->aiProcessing = false;
        $this->aiStatus = 'Analysis failed';
        Toaster::error('AI analysis failed: ' . $event['error']);

        // Update UI
        $this->dispatch('status-updated');
    }

    // Polling fallback - check status setiap 2 detik
    public function pollStatus()
    {
        if (!$this->aiProcessing) {
            return;
        }

        $cacheKey = "ai_analysis_{$this->position->id}";
        $cachedResults = Cache::get($cacheKey);

        if ($cachedResults) {
            $this->recommendations = $cachedResults;
            $this->aiProcessing = false;
            $this->aiProgress = 100;
            $this->aiStatus = 'Analysis complete!';
            Toaster::success('AI analysis complete!');
            return;
        }

        // Check progress dari cache
        $progressKey = "ai_progress_{$this->position->id}";
        $progress = Cache::get($progressKey);

        if ($progress) {
            $this->aiProgress = $progress['progress'];
            $this->aiStatus = $progress['message'];
        }
    }

    public function updatedSelectedCandidates($value)
    {
        if (count($this->selectedCandidates) > 5) {
            $this->selectedCandidates = array_slice($this->selectedCandidates, 0, 5);
            Toaster::warning('Maximum 5 candidates can be selected');
        }
    }

    public function compareSelected()
    {
        if (count($this->selectedCandidates) < 2) {
            Toaster::warning('Please select at least 2 candidates to compare');
            return;
        }

        // return redirect()->route('vacant-positions.compare', [
        //     'slug' => $this->positionSlug,
        //     'candidates' => implode(',', $this->selectedCandidates)
        // ]);
    }

    public function getUserIdProperty()
    {
        return Auth::id();
    }

    public function getPositionIdProperty()
    {
        return $this->position->id;
    }

    public function render()
    {
        return view('livewire.pages.vacant-positions.show', [
            'position' => $this->position,
            'candidates' => $this->recommendations
        ]);
    }
}
