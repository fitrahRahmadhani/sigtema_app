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
    public $showEmployeeModal = false;
    public $searchTerm = '';
    public $availableEmployees = [];
    public $selectedForAnalysis = [];
    public $maxCandidates = 20;
    public $user;

    public function mount(Position $position)
    {
        $this->user = Auth::user();

        if ($this->user->hasRole('head-of-unit') && $this->user->band >= $position->band) {
            return redirect()->route('vacant-positions.index')->warning('You do not have permission to access this page.');
        }
        $this->position = $position;
        $this->positionSlug = $position->slug;

        // Check for cached results
        $cacheKey = "ai_analysis_{$this->position->id}";
        $cachedResults = Cache::get($cacheKey);

        if ($cachedResults) {
            $this->recommendations = $cachedResults;
            $this->aiProcessing = false;
            $this->aiProgress = 100;
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

    public function updatedSearchTerm($value)
    {
        if (strlen($value) >= 2) {
            $this->searchEmployees();
        } elseif (empty($value)) {
            $this->availableEmployees = [];
        }
    }

    public function searchEmployees()
    {
        $this->availableEmployees = Employee::with('position')
            ->where('company_id', $this->position->company_id)

            // Jika user role head-of-unit, tampilkan employee dengan band >= band user
            ->when($this->user->hasRole('head-of-unit'), function ($q) {
                $q->where('band', '>=', (int) $this->user->band);
            })

            // Filter berdasarkan searchTerm (CASE-INSENSITIVE)
            ->when($this->searchTerm, function ($q) {
                $searchTerm = strtolower($this->searchTerm); // Convert ke lowercase
                $q->where(function ($query) use ($searchTerm) {
                    $query->whereRaw('LOWER(name) LIKE ?', ['%' . $searchTerm . '%'])
                        ->orWhereRaw('LOWER(employee_number) LIKE ?', ['%' . $searchTerm . '%']);
                });
            })

            // Kecualikan employee yang sudah dipilih
            ->when(!empty($this->selectedForAnalysis), function ($q) {
                $q->whereNotIn('id', $this->selectedForAnalysis);
            })

            ->limit(50)
            ->get()
            ->map(fn($employee) => [
                'id' => $employee->id,
                'name' => $employee->name,
                'employee_number' => $employee->employee_number,
                'band' => $employee->band,
                'position' => $employee->position->name ?? 'No Position',
            ])
            ->toArray();
    }



    public function addToAnalysis($employeeId)
    {
        if (count($this->selectedForAnalysis) >= $this->maxCandidates) {
            Toaster::warning("Maximum {$this->maxCandidates} candidates can be analyzed");
            return;
        }

        if (!in_array($employeeId, $this->selectedForAnalysis)) {
            $this->selectedForAnalysis[] = $employeeId;
            $this->availableEmployees = array_filter($this->availableEmployees, function ($emp) use ($employeeId) {
                return $emp['id'] != $employeeId;
            });
        }
    }

    public function removeFromAnalysis($employeeId)
    {
        $this->selectedForAnalysis = array_filter($this->selectedForAnalysis, function ($id) use ($employeeId) {
            return $id != $employeeId;
        });
    }

    public function startAIProcessing()
    {
        if (count($this->selectedForAnalysis) === 0) {
            Toaster::warning('Please select at least 1 candidate to analyze');
            return;
        }

        $this->aiProcessing = true;
        $this->aiProgress = 0;
        $this->aiStatus = 'Preparing AI analysis...';
        $this->showEmployeeModal = false;

        // Dispatch job dengan kandidat terpilih
        ProcessAIRecommendationsJob::dispatch(
            $this->position,
            Auth::id(),
            $this->selectedForAnalysis
        );

        Toaster::info('AI analysis started. Process may take 1-2 minutes.');
    }

    public function retryAIProcessing()
    {
        Cache::forget("ai_analysis_{$this->position->id}");
        $this->recommendations = [];
        $this->selectedForAnalysis = [];
        $this->showEmployeeModal = true;
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

        Toaster::success('AI analysis complete! Best recommendations have been found.');
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

        if (count($this->selectedCandidates) > 5) {
            Toaster::warning('Maximum 5 candidates can be compared');
            return;
        }

        return redirect()->route('vacant-positions.compare', [
            'position' => $this->positionSlug,
            'candidates' => implode(',', $this->selectedCandidates)
        ]);
    }

    public function render()
    {
        return view('livewire.pages.vacant-positions.show', [
            'position' => $this->position,
            'candidates' => $this->recommendations
        ]);
    }
}
