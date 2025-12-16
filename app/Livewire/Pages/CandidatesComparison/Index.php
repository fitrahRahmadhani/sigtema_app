<?php

namespace App\Livewire\Pages\CandidatesComparison;

use App\Models\Company;
use Livewire\Component;
use App\Models\Employee;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
#[Title('Employee Comparison')]
class Index extends Component
{
    public $selectedCompanyId = null;
    public $selectedCompany = null;
    public $companies = [];
    public $selectedEmployees = [];
    public $searchTerms = ['', '', ''];
    public $searchResults = [[], [], []];
    public $showSearchDropdown = [false, false, false];
    public $columnCount = 3;
    public $isSuperadmin = false;

    public function mount()
    {
        $user = Auth::user();

        $this->isSuperadmin = $user->hasRole('superadmin');

        if ($this->isSuperadmin) {
            $this->companies = Company::where('is_active', true)->get();
            $this->selectedCompanyId = session('candidates_comparison_company_id') ?? request('company_id');

            if ($this->selectedCompanyId) {
                $this->selectedCompany = Company::find($this->selectedCompanyId);
            }
        } else {
            $this->selectedCompanyId = $user->company_id;
            $this->selectedCompany = Company::find($this->selectedCompanyId);
            $this->companies = Company::where('id', $user->company_id)->get();
        }
    }

    public function selectCompany($companyId)
    {
        $this->selectedCompanyId = $companyId;
        $this->selectedCompany = Company::find($companyId);
        session(['candidates_comparison_company_id' => $companyId]);

        // Reset employee selections when changing company
        $this->resetEmployeeData();
    }

    public function changeCompany()
    {
        $this->selectedCompanyId = null;
        $this->selectedCompany = null;
        session()->forget('candidates_comparison_company_id');

        // Reset employee selections
        $this->resetEmployeeData();
    }

    private function resetEmployeeData()
    {
        $this->selectedEmployees = array_fill(0, $this->columnCount, null);
        $this->searchTerms = array_fill(0, $this->columnCount, '');
        $this->searchResults = array_fill(0, $this->columnCount, []);
        $this->showSearchDropdown = array_fill(0, $this->columnCount, false);
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
        // Don't search if no company selected
        if (!$this->selectedCompanyId) {
            return;
        }

        if (strlen($value) >= 3) {
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
                'innovations',
                'company'
            ])
                ->where('company_id', $this->selectedCompanyId)
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
            'innovations',
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
        $showCompanySelection = $this->isSuperadmin && !$this->selectedCompanyId;

        return view('livewire.pages.candidates-comparison.index', [
            'showCompanySelection' => $showCompanySelection,
            'companies' => $this->companies,
            'isSuperadmin' => $this->isSuperadmin,
            'selectedCompany' => $this->selectedCompany
        ]);
    }
}
