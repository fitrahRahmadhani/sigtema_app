<?php

namespace App\Livewire\Pages\VacantPositions;

use App\Jobs\ProcessAIRecommendationsJob;
use App\Models\Employee;
use App\Models\EmployeeHierarchy;
use App\Models\Position;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
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
    public $canSearchEmployees = true;
    public $hasSubordinates = false;

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

        $this->checkSearchAccess();
    }

    /**
     * Determine if the current user has permission to search and add employees.
     *
     * Rules:
     * - Superadmin / Admin / HC-Operator → full access to all employees within the company
     * - Band 4+ (Head of Unit)           → can search employees at same band or lower
     * - Band 3 with subordinates         → can only search their direct subordinates
     * - Band 3 without subordinates      → NO access at all
     */
    private function checkSearchAccess(): void
    {
        // HC-Operator has the same full access as Admin, regardless of band
        if ($this->user->hasRole('hc-operator')) {
            $this->canSearchEmployees = true;
            $this->hasSubordinates    = true;
            return;
        }

        if ($this->user->band === 3) {
            $subordinateIds        = $this->getSubordinateIds();
            $this->hasSubordinates = count($subordinateIds) > 0;

            // Band 3 without subordinates → cannot search
            $this->canSearchEmployees = $this->hasSubordinates;
        } else {
            // Band 4+, Admin, Superadmin → full access
            $this->canSearchEmployees = true;
            $this->hasSubordinates    = true;
        }
    }

    /**
     * Find the Employee record that corresponds to the logged-in User.
     * Bridge between users and employees tables is the email column.
     */
    private function getUserEmployee(): ?Employee
    {
        return Employee::where('email', $this->user->email)
            ->where('company_id', $this->user->company_id)
            ->first();
    }

    /**
     * Get the Position record that belongs to the current user's Employee record.
     * positions.employee_id → employees.id
     */
    private function getUserPosition(): ?Position
    {
        $employee = $this->getUserEmployee();

        if (!$employee) {
            return null;
        }

        return Position::where('employee_id', $employee->id)->first();
    }

    /**
     * Get IDs of all direct subordinate employees under the current user.
     *
     * Flow:
     *   users.email
     *     → employees.email          (find employee record)
     *     → positions.employee_id    (find their position)
     *     → employee_hierarchies.superior_id = position.id (find subordinate positions)
     *     → positions.employee_id    (find employees in those positions)
     *     → employees.id             (return their IDs)
     */
    public function getSubordinateIds(): array
    {
        $userPosition = $this->getUserPosition();

        if (!$userPosition) {
            return [];
        }

        // Get all position IDs that are direct subordinates of the user's position
        $subordinatePositionIds = EmployeeHierarchy::where('company_id', $this->user->company_id)
            ->where('superior_id', $userPosition->id)
            ->pluck('position_id')
            ->toArray();

        if (empty($subordinatePositionIds)) {
            return [];
        }

        // Get employee IDs that currently hold those subordinate positions
        return Employee::where('company_id', $this->user->company_id)
            ->whereIn('id', function ($query) use ($subordinatePositionIds) {
                $query->select('employee_id')
                    ->from('positions')
                    ->whereIn('id', $subordinatePositionIds)
                    ->whereNotNull('employee_id');
            })
            ->pluck('id')
            ->toArray();
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

        // Guard: user without permission cannot search
        if (!$this->canSearchEmployees) {
            Toaster::warning('You do not have permission to search employees.');
            return;
        }

        $selectedIds = array_keys($this->selectedForAnalysis);

        $query = Employee::with(['position:id,name,employee_id'])
            ->select(['id', 'name', 'employee_number', 'band', 'company_id'])
            ->where('company_id', $this->position->company_id)
            ->where(function ($q) {
                $q->where('name', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('employee_number', 'LIKE', '%' . $this->searchTerm . '%');
            })
            ->whereNotIn('id', $selectedIds)
            ->limit(20);

        if ($this->user->band === 3 && $this->hasSubordinates && ! $this->user->hasRole('hc-operator')) {
            // Band 3 with subordinates → can ONLY search their direct subordinates
            $subordinateIds = $this->getSubordinateIds();

            if (empty($subordinateIds)) {
                $this->availableEmployees = [];
                return;
            }

            $query->whereIn('id', $subordinateIds);
        } elseif (!$this->user->hasRole('superadmin|admin|hc-operator')) {
            // Band 4+ Head of Unit → can search same band or lower
            // Guard against null band
            $userBand = (int) ($this->user->band ?? 1);
            $query->where('band', '>=', $userBand);
        }
        // Superadmin / Admin / HC-Operator → no additional filter, full company access

        $this->availableEmployees = $query->get()
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
        if (!$this->canSearchEmployees) {
            Toaster::warning('You do not have permission to add employees.');
            return;
        }

        if (count($this->selectedForAnalysis) >= $this->maxCandidates) {
            Toaster::warning("Maximum {$this->maxCandidates} candidates can be analyzed.");
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

        if (count($employeeIds) < 1) {
            Toaster::warning('Select at least 1 candidate to analyze.');
            return;
        }

        if (count($employeeIds) > $this->maxCandidates) {
            Toaster::warning("Maximum {$this->maxCandidates} candidates per analysis.");
            return;
        }

        $this->aiProcessing      = true;
        $this->aiProgress        = 0;
        $this->aiStatus          = 'Preparing AI analysis...';
        $this->showEmployeeModal = false;

        ProcessAIRecommendationsJob::dispatch(
            $this->position,
            Auth::id(),
            $employeeIds
        );

        Toaster::info('AI analysis started. Process may take 1 - 2 minutes.');

        Log::info('(VC) User ' . Auth::user()->name . ' (' . Auth::user()->email . ')' . ' started AI analysis for position ' . $this->position->name);
        activity()
            ->event('Vacant Position Analysis')
            ->withProperties(['actor' => Auth::user()->name, 'position' => $this->position->name])
            ->log('Actor started AI analysis for position ' . $this->position->name);
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
        $this->aiStatus        = 'Analysis complete!';

        Cache::put($this->cacheKey(), $event['results'], now()->addHours(24));

        Toaster::success('AI analysis complete! Best recommendations have been found.');
    }

    public function handleAIFailed($event)
    {
        $this->aiProcessing = false;
        $this->aiStatus     = 'Analysis failed';
        Toaster::error('AI Analysis Failed: ' . $event['error']);
    }

    public function updatedSelectedCandidates($value)
    {
        if (count($this->selectedCandidates) > 5) {
            $this->selectedCandidates = array_slice($this->selectedCandidates, 0, 5);
            Toaster::warning('Maximum 5 candidates can be selected for comparison.');
        }
    }

    public function compareSelected()
    {
        if (count($this->selectedCandidates) < 2) {
            Toaster::warning('Comparison must have at least 2 candidates.');
            return;
        }

        if (count($this->selectedCandidates) > 5) {
            Toaster::warning('Max. 5 candidates can be compared.');
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