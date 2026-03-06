<?php

namespace App\Livewire\Pages\VacantPositions;

use App\Jobs\ProcessAIRecommendationsJob;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
class Show extends Component
{
    #[Title('Candidates Match')]

    public User $user;
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
    public $maxCandidates = 10;

    public function mount(Position $position)
    {
        $this->user = Auth::user();

        if ($this->user->hasRole('head-of-unit') && $this->user->band > $position->band) {
            return redirect()->route('vacant-positions.index')->warning('You do not have permission to access this page.');
        }

        $this->position     = $position;
        $this->positionSlug = $position->slug;

        $cachedResults = Cache::get($this->cacheKey());

        if ($cachedResults) {
            $this->recommendations = $cachedResults;
            $this->aiProcessing    = false;
            $this->aiProgress      = 100;
        }
    }

    private function cacheKey(): string
    {
        return 'ai_analysis_' . Auth::id() . '_' . $this->position->id;
    }

    protected function getListeners()
    {
        $userId     = Auth::id();
        $positionId = $this->position->id;

        return [
            "echo-private:ai-analysis.{$userId}.{$positionId},.ai.progress"  => 'handleAIProgress',
            "echo-private:ai-analysis.{$userId}.{$positionId},.ai.completed" => 'handleAICompleted',
            "echo-private:ai-analysis.{$userId}.{$positionId},.ai.failed"    => 'handleAIFailed',
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
        if (strlen($this->searchTerm) < 2) {
            $this->availableEmployees = [];
            return;
        }

        $selectedIds = array_keys($this->selectedForAnalysis);

        $this->availableEmployees = Employee::with(['position:id,name,employee_id'])
            ->select(['id', 'name', 'employee_number', 'band', 'company_id'])
            ->where('company_id', $this->position->company_id)
            ->when(
                $this->user->hasRole('head-of-unit'),
                fn($q) =>
                $q->where('band', '>=', (int) $this->user->band)
            )
            ->where(function ($q) {
                $q->where('name', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('employee_number', 'LIKE', '%' . $this->searchTerm . '%');
            })
            ->when(
                !empty($selectedIds),
                fn($q) =>
                $q->whereNotIn('id', $selectedIds)
            )
            ->limit(20)
            ->get()
            ->mapWithKeys(fn($employee) => [
                $employee->id => [
                    'id'              => $employee->id,
                    'name'            => $employee->name,
                    'employee_number' => $employee->employee_number,
                    'band'            => $employee->band,
                    'position'        => $employee->position?->name ?? 'No Position',
                ]
            ])
            ->toArray();
    }

    public function addToAnalysis($employeeId)
    {
        if (count($this->selectedForAnalysis) >= $this->maxCandidates) {
            Toaster::warning("Maksimal {$this->maxCandidates} kandidat dapat dianalisis");
            return;
        }

        if (isset($this->availableEmployees[$employeeId])) {
            $this->selectedForAnalysis[$employeeId] = $this->availableEmployees[$employeeId];
            unset($this->availableEmployees[$employeeId]);
        }
    }

    public function removeFromAnalysis($employeeId)
    {
        if (isset($this->selectedForAnalysis[$employeeId])) {
            $this->availableEmployees[$employeeId] = $this->selectedForAnalysis[$employeeId];
            unset($this->selectedForAnalysis[$employeeId]);
        }
    }

    public function startAIProcessing()
    {
        $employeeIds = array_keys($this->selectedForAnalysis);

        if (count($employeeIds) === 0) {
            Toaster::warning('Pilih minimal 1 kandidat untuk dianalisis');
            return;
        }

        if (count($employeeIds) > $this->maxCandidates) {
            Toaster::warning("Maksimal {$this->maxCandidates} kandidat per analisis");
            return;
        }

        $this->aiProcessing      = true;
        $this->aiProgress        = 0;
        $this->aiStatus          = 'Mempersiapkan analisis AI...';
        $this->showEmployeeModal = false;

        ProcessAIRecommendationsJob::dispatch(
            $this->position,
            Auth::id(),
            $employeeIds
        );

        Toaster::info('Analisis AI dimulai. Proses membutuhkan 1 - 2 menit.');
    }

    public function retryAIProcessing()
    {
        Cache::forget($this->cacheKey());
        $this->recommendations     = [];
        $this->selectedForAnalysis = [];
        $this->availableEmployees  = [];
        $this->searchTerm          = '';
        $this->showEmployeeModal   = true;
    }

    public function handleAIProgress($event)
    {
        $this->aiProgress = $event['progress'];
        $this->aiStatus   = $event['message'];
    }

    public function handleAICompleted($event)
    {
        $this->recommendations = $event['results'];
        $this->aiProcessing    = false;
        $this->aiProgress      = 100;
        $this->aiStatus        = 'Analisis selesai!';

        Cache::put($this->cacheKey(), $event['results'], now()->addHours(24));

        Toaster::success('Analisis AI selesai! Rekomendasi terbaik telah ditemukan.');
    }

    public function handleAIFailed($event)
    {
        $this->aiProcessing = false;
        $this->aiStatus     = 'Analisis gagal';
        Toaster::error('Analisis AI gagal: ' . $event['error']);
    }

    public function updatedSelectedCandidates($value)
    {
        if (count($this->selectedCandidates) > 5) {
            $this->selectedCandidates = array_slice($this->selectedCandidates, 0, 5);
            Toaster::warning('Maksimal 5 kandidat dapat dipilih untuk perbandingan');
        }
    }

    public function compareSelected()
    {
        if (count($this->selectedCandidates) < 2) {
            Toaster::warning('Pilih minimal 2 kandidat untuk dibandingkan');
            return;
        }

        if (count($this->selectedCandidates) > 5) {
            Toaster::warning('Maksimal 5 kandidat dapat dibandingkan');
            return;
        }

        return redirect()->route('vacant-positions.compare', [
            'position'   => $this->positionSlug,
            'candidates' => implode(',', $this->selectedCandidates)
        ]);
    }

    public function render()
    {
        return view('livewire.pages.vacant-positions.show', [
            'position'   => $this->position,
            'candidates' => $this->recommendations
        ]);
    }
}
