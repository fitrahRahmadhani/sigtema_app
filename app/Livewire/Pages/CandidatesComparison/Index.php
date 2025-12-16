<?php

namespace App\Livewire\Pages\CandidatesComparison;

use Livewire\Component;
use App\Models\Employee;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
class Index extends Component
{
    #[Title('Employee Comparison')]

    public $companyId;
    public $selectedEmployees = [];
    public $searchTerms = ['', '', ''];
    public $searchResults = [[], [], []];
    public $showSearchDropdown = [false, false, false];
    public $columnCount = 3;

    public function mount()
    {
        $this->companyId = Auth::user()->company_id;
    }

    public function addColumn()
    {
        if ($this->columnCount < 5) {
            $this->columnCount++;
            $this->searchTerms[] = '';
            $this->searchResults[] = [];
            $this->showSearchDropdown[] = false;
            $this->selectedEmployees[] = null;
        }
    }

    public function removeColumn($index)
    {
        if ($this->columnCount > 2) {
            array_splice($this->searchTerms, $index, 1);
            array_splice($this->searchResults, $index, 1);
            array_splice($this->showSearchDropdown, $index, 1);
            array_splice($this->selectedEmployees, $index, 1);
            $this->columnCount--;
        }
    }

    public function updatedSearchTerms($value, $index)
    {
        if (strlen($value) >= 4) {
            $selectedIds = collect($this->selectedEmployees)
                ->filter()
                ->pluck('id')
                ->toArray();

            $this->searchResults[$index] = Employee::with([
                'position',
                'educations',
                'technicalCompetencies',
                'mcuResults',
                'positionHistories',
                'company'
            ])
                ->where('company_id', $this->companyId)
                ->where(function ($query) use ($value) {
                    $query->where('name', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%")
                        ->orWhere('employee_number', 'like', "%{$value}%")
                        ->orWhereHas('position', function ($positionQuery) use ($value) {
                            $positionQuery->where('name', 'like', "%{$value}%");
                        });
                })
                ->whereNotIn('id', $selectedIds)
                ->limit(5)
                ->get();

            $this->showSearchDropdown[$index] = !empty($this->searchResults[$index]);
        } else {
            $this->searchResults[$index] = [];
            $this->showSearchDropdown[$index] = false;
        }
    }

    public function selectEmployee($employeeId, $index)
    {
        $employee = Employee::with([
            'position',
            'educations',
            'technicalCompetencies',
            'mcuResults',
            'positionHistories',
            'company'
        ])->find($employeeId);

        if ($employee) {
            $this->selectedEmployees[$index] = $employee;
            $this->searchTerms[$index] = $employee->name;
            $this->showSearchDropdown[$index] = false;
            $this->searchResults[$index] = [];
        }
    }

    public function clearEmployee($index)
    {
        $this->selectedEmployees[$index] = null;
        $this->searchTerms[$index] = '';
        $this->searchResults[$index] = [];
        $this->showSearchDropdown[$index] = false;
    }

    public function render()
    {
        return view('livewire.pages.candidates-comparison.index');
    }
}
