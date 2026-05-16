<?php

namespace App\Livewire\Pages\EmployeesProfile;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Employee Profile')]
class Index extends Component
{
    use WithPagination;

    public User $user;

    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'gender')]
    public $filterGender = '';

    #[Url(as: 'unit')]
    public $filterUnit = '';

    #[Url(as: 'dept')]
    public $filterDepartment = '';

    #[Url(as: 'band')]
    public $filterBand = '';

    #[Url]
    public $selectedCompanyId = null;

    public $companies = [];
    public $isSuperadmin = false;
    public $selectedCompany = null;

    // Options for dropdowns (will be filtered based on other selected filters)
    public $bandOptions = [];
    public $unitOptions = [];
    public $departmentOptions = [];

    public function mount()
    {
        $this->user = Auth::user();

        $this->isSuperadmin = $this->user->hasRole('superadmin');

        if ($this->isSuperadmin) {
            $this->companies = Company::where('is_active', true)->get();
            $this->selectedCompanyId = session('employees_profile_company_id') ?? request('company_id');

            if ($this->selectedCompanyId) {
                $this->selectedCompany = Company::find($this->selectedCompanyId);
            }
        } else {
            $this->selectedCompanyId = $this->user->company_id;
            $this->selectedCompany = Company::find($this->selectedCompanyId);
            $this->companies = Company::where('id', $this->user->company_id)->get();
        }
    }

    public function selectCompany($companyId)
    {
        $this->selectedCompanyId = $companyId;
        $this->selectedCompany = Company::find($companyId);
        session(['employees_profile_company_id' => $companyId]);
        $this->resetPage();
        $this->resetFilters();
    }

    public function changeCompany()
    {
        $this->selectedCompanyId = null;
        $this->selectedCompany = null;
        session()->forget('employees_profile_company_id');
        $this->resetPage();
        $this->resetFilters();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterGender()
    {
        $this->resetPage();
    }

    public function updatingFilterUnit()
    {
        $this->resetPage();
    }

    public function updatingFilterDepartment()
    {
        $this->resetPage();
    }

    public function updatingFilterBand()
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->filterGender = '';
        $this->filterUnit = '';
        $this->filterDepartment = '';
        $this->filterBand = '';
        $this->resetPage();
    }

    /**
     * Get unique work units from position data
     * Returns unique values from section, biro, department, directorate columns
     */
    private function getUniqueWorkUnits($positions): array
    {
        $units = collect();

        foreach ($positions as $position) {
            // Collect values from all unit columns
            if ($position->section) {
                $units->push($position->section);
            }
            if ($position->biro) {
                $units->push($position->biro);
            }
            if ($position->department) {
                $units->push($position->department);
            }
            if ($position->directorate) {
                $units->push($position->directorate);
            }
        }

        return $units->unique()->sort()->values()->toArray();
    }

    /**
     * Build filter options based on current selected filters
     * This makes the dropdown options dependent on each other
     */
    private function buildFilterOptions(): void
    {
        $companyId = $this->selectedCompanyId ?? Auth::user()->company_id;

        // Get all employee IDs for this company
        $companyEmployeeIds = Employee::where('company_id', $companyId)->pluck('id')->toArray();

        // Build base query for positions (without current filters)
        $basePositionQuery = Position::whereIn('employee_id', $companyEmployeeIds);

        // Build base query for employees (without current filters)
        $baseEmployeeQuery = Employee::where('company_id', $companyId);

        // Get all positions data first
        $allPositions = (clone $basePositionQuery)->get();

        // Get all unique values
        $allBands = (clone $baseEmployeeQuery)
            ->whereNotNull('band')
            ->distinct()
            ->orderBy('band')
            ->pluck('band')
            ->toArray();

        $allDepartments = $allPositions
            ->whereNotNull('department')
            ->pluck('department')
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        // Get all work units (from all unit columns)
        $allUnits = $this->getUniqueWorkUnits($allPositions);

        // Start with all options
        $this->bandOptions = $allBands;
        $this->departmentOptions = $allDepartments;
        $this->unitOptions = $allUnits;

        // Apply dependent filtering - start from bands, then filter departments and units
        // based on employees in those bands
        if (!empty($this->filterBand)) {
            $bandEmployeeIds = (clone $baseEmployeeQuery)
                ->where('band', $this->filterBand)
                ->pluck('id')
                ->toArray();

            $bandPositions = Position::whereIn('employee_id', $bandEmployeeIds)->get();

            $this->departmentOptions = array_values(array_intersect(
                $this->departmentOptions,
                $bandPositions->whereNotNull('department')->pluck('department')->unique()->sort()->values()->toArray()
            ));

            $this->unitOptions = array_values(array_intersect(
                $this->unitOptions,
                $this->getUniqueWorkUnits($bandPositions)
            ));
        }

        // Apply department filter - get bands and units from employees in that department
        if (!empty($this->filterDepartment)) {
            $deptPositions = (clone $basePositionQuery)
                ->where('department', $this->filterDepartment)
                ->get();

            $deptEmployeeIds = $deptPositions->pluck('employee_id')->toArray();

            // Filter bands based on employees in this department
            $this->bandOptions = array_values(array_intersect(
                $this->bandOptions,
                (clone $baseEmployeeQuery)->whereIn('id', $deptEmployeeIds)
                    ->whereNotNull('band')
                    ->distinct()
                    ->orderBy('band')
                    ->pluck('band')
                    ->toArray()
            ));

            // Filter units based on positions in this department
            $this->unitOptions = array_values(array_intersect(
                $this->unitOptions,
                $this->getUniqueWorkUnits($deptPositions)
            ));
        }

        // Apply unit filter - get bands and departments from positions with this unit
        if (!empty($this->filterUnit)) {
            $unitPositions = (clone $basePositionQuery)
                ->where(function ($q) {
                    $q->where('section', $this->filterUnit)
                        ->orWhere('biro', $this->filterUnit)
                        ->orWhere('department', $this->filterUnit)
                        ->orWhere('directorate', $this->filterUnit);
                })
                ->get();

            $unitEmployeeIds = $unitPositions->pluck('employee_id')->toArray();

            // Filter bands based on employees in positions with this unit
            $this->bandOptions = array_values(array_intersect(
                $this->bandOptions,
                (clone $baseEmployeeQuery)->whereIn('id', $unitEmployeeIds)
                    ->whereNotNull('band')
                    ->distinct()
                    ->orderBy('band')
                    ->pluck('band')
                    ->toArray()
            ));

            // Filter departments based on positions with this unit
            $this->departmentOptions = array_values(array_intersect(
                $this->departmentOptions,
                $unitPositions->whereNotNull('department')->pluck('department')->unique()->sort()->values()->toArray()
            ));
        }

        // Reset invalid selections if they no longer exist in filtered options
        if (!empty($this->filterBand) && !in_array($this->filterBand, $this->bandOptions)) {
            $this->filterBand = '';
        }
        if (!empty($this->filterDepartment) && !in_array($this->filterDepartment, $this->departmentOptions)) {
            $this->filterDepartment = '';
        }
        if (!empty($this->filterUnit) && !in_array($this->filterUnit, $this->unitOptions)) {
            $this->filterUnit = '';
        }
    }

    public function render()
    {
        // Jika superadmin belum pilih company, tidak perlu build filter options
        if ($this->isSuperadmin && !$this->selectedCompanyId) {
            return view('livewire.pages.employees-profile.index', [
                'showCompanySelection' => true,
                'companies' => $this->companies,
                'isSuperadmin' => $this->isSuperadmin,
                'selectedCompany' => null,
                'employees' => collect(),
                'bands' => [],
                'units' => [],
                'departments' => [],
                'total' => 0
            ]);
        }

        // Build dependent filter options (only when company is selected)
        $this->buildFilterOptions();

        // Tentukan company ID
        $companyId = $this->selectedCompanyId ?? Auth::user()->company_id;
        $selectedCompany = Company::find($companyId);

        // Query employees dengan position
        $query = Employee::with(['position'])
            ->where('company_id', $companyId);

        // Filter search
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('employee_number', 'like', '%' . $this->search . '%');
            });
        }

        // Filter gender
        if (!empty($this->filterGender)) {
            $query->where('gender', $this->filterGender);
        }

        // Filter department
        if (!empty($this->filterDepartment)) {
            $query->whereHas('position', function ($q) {
                $q->where('department', $this->filterDepartment);
            });
        }

        // Filter band
        if (!empty($this->filterBand)) {
            $query->where('band', $this->filterBand);
        }

        // Filter unit menggunakan work_unit
        if (!empty($this->filterUnit)) {
            $query->whereHas('position', function ($q) {
                // Filter berdasarkan semua level: section, biro, department, directorate
                $q->where('section', $this->filterUnit)
                    ->orWhere('biro', $this->filterUnit)
                    ->orWhere('department', $this->filterUnit)
                    ->orWhere('directorate', $this->filterUnit);
            });
        }

        // Paginate
        $employees = $query->paginate(10);

        return view('livewire.pages.employees-profile.index', [
            'showCompanySelection' => false,
            'employees' => $employees,
            'total' => $employees->total(),
            'bands' => $this->bandOptions,
            'units' => $this->unitOptions,
            'departments' => $this->departmentOptions,
            'selectedCompany' => $selectedCompany,
            'isSuperadmin' => $this->isSuperadmin,
            'companies' => $this->companies
        ]);
    }
}
