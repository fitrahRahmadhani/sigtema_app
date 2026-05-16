<?php

namespace App\Livewire\Pages\VacantPositions;

use App\Models\Company;
use Livewire\Component;
use App\Models\Position;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

#[Layout('components.layouts.app')]
#[Title('Vacant Positions')]
class Index extends Component
{
    use WithPagination;

    public $selectedCompanyId = null;
    public $selectedCompany = null;
    public $companies = [];
    public $isSuperadmin = false;

    public string $search = '';
    public string $filterDepartment = '';
    public string $filterUnit = '';
    public string $filterBand = '';
    public string $activeTab = 'vacant';

    // Options for dropdowns (will be filtered based on other selected filters)
    public $departmentOptions = [];
    public $unitOptions = [];
    public $bandOptions = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'filterDepartment' => ['except' => ''],
        'filterUnit' => ['except' => ''],
        'filterBand' => ['except' => ''],
        'activeTab' => ['except' => 'vacant'],
        'page' => ['except' => 1]
    ];

    public function mount(): void
    {
        /** @var \App\Models\User */
        $user = Auth::user();

        $this->isSuperadmin = $user->hasRole('superadmin');

        if ($this->isSuperadmin) {
            $this->companies = Company::where('is_active', true)->get();
            $this->selectedCompanyId = session('vacant_positions_company_id') ?? request('company_id');

            if ($this->selectedCompanyId) {
                $this->selectedCompany = Company::find($this->selectedCompanyId);
            }
        } else {
            $this->selectedCompanyId = $user->company_id;
            $this->selectedCompany = Company::find($this->selectedCompanyId);
            $this->companies = Company::where('id', $user->company_id)->get();
        }

        // Set default tab from query string or session
        $this->activeTab = request('activeTab', session('vacant_positions_active_tab', 'vacant'));
    }

    public function selectCompany($companyId)
    {
        $this->selectedCompanyId = $companyId;
        $this->selectedCompany = Company::find($companyId);
        session(['vacant_positions_company_id' => $companyId]);
        $this->resetPage();
        $this->resetFilters();
    }

    public function changeCompany()
    {
        $this->selectedCompanyId = null;
        $this->selectedCompany = null;
        session()->forget('vacant_positions_company_id');
        $this->resetPage();
        $this->resetFilters();
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        session(['vacant_positions_active_tab' => $tab]);
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->filterDepartment = '';
        $this->filterUnit = '';
        $this->filterBand = '';
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterDepartment(): void
    {
        // When department changes, reset unit if it doesn't exist in new department options
        $this->resetPage();
    }

    public function updatedFilterUnit(): void
    {
        // When unit changes, reset department and band if they don't exist in new options
        $this->resetPage();
    }

    public function updatedFilterBand(): void
    {
        // When band changes, reset department and unit if they don't exist in new options
        $this->resetPage();
    }

    public function updatedActiveTab(): void
    {
        $this->resetPage();
    }

    /**
     * Build base query for positions (with company filter)
     */
    private function getBaseQuery(): Builder
    {
        $query = Position::where('company_id', $this->selectedCompanyId);

        if ($this->activeTab === 'vacant') {
            $query->whereNull('employee_id');
        }

        return $query;
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
        // Build base query with consistent filters
        $basePositionQuery = Position::where('company_id', $this->selectedCompanyId);
        if ($this->activeTab === 'vacant') {
            $basePositionQuery->whereNull('employee_id');
        }

        // Get all positions data first
        $allPositions = (clone $basePositionQuery)->get();

        // Get all departments
        $allDepartments = $allPositions
            ->whereNotNull('department')
            ->pluck('department')
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        // Get all bands
        $allBands = $allPositions
            ->whereNotNull('band')
            ->pluck('band')
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        // Get all work units (from all unit columns)
        $allUnits = $this->getUniqueWorkUnits($allPositions);

        // Start with all options
        $this->departmentOptions = $allDepartments;
        $this->bandOptions = $allBands;
        $this->unitOptions = $allUnits;

        // If band is selected, filter departments and units based on that band
        if (!empty($this->filterBand)) {
            $bandFilteredPositions = (clone $basePositionQuery)
                ->where('band', $this->filterBand)
                ->get();

            $this->departmentOptions = array_values(array_intersect(
                $this->departmentOptions,
                $bandFilteredPositions->whereNotNull('department')->pluck('department')->unique()->sort()->values()->toArray()
            ));

            $this->unitOptions = array_values(array_intersect(
                $this->unitOptions,
                $this->getUniqueWorkUnits($bandFilteredPositions)
            ));
        }

        // If department is selected, filter bands and units based on that department
        if (!empty($this->filterDepartment)) {
            $deptFilteredPositions = (clone $basePositionQuery)
                ->where('department', $this->filterDepartment)
                ->get();

            $this->bandOptions = array_values(array_intersect(
                $this->bandOptions,
                $deptFilteredPositions->whereNotNull('band')->pluck('band')->unique()->sort()->values()->toArray()
            ));

            $this->unitOptions = array_values(array_intersect(
                $this->unitOptions,
                $this->getUniqueWorkUnits($deptFilteredPositions)
            ));
        }

        // If unit is selected, filter bands and departments based on that unit
        if (!empty($this->filterUnit)) {
            $unitFilteredPositions = (clone $basePositionQuery)
                ->where(function ($q) {
                    $q->where('section', $this->filterUnit)
                        ->orWhere('biro', $this->filterUnit)
                        ->orWhere('department', $this->filterUnit)
                        ->orWhere('directorate', $this->filterUnit);
                })
                ->get();

            $this->bandOptions = array_values(array_intersect(
                $this->bandOptions,
                $unitFilteredPositions->whereNotNull('band')->pluck('band')->unique()->sort()->values()->toArray()
            ));

            $this->departmentOptions = array_values(array_intersect(
                $this->departmentOptions,
                $unitFilteredPositions->whereNotNull('department')->pluck('department')->unique()->sort()->values()->toArray()
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

    public function getStats()
    {
        if (!$this->selectedCompanyId) {
            return [
                'total' => 0,
                'vacant' => 0,
                'filled' => 0
            ];
        }

        $total = Position::where('company_id', $this->selectedCompanyId)->count();
        $vacant = Position::where('company_id', $this->selectedCompanyId)
            ->whereNull('employee_id')
            ->count();
        $filled = $total - $vacant;

        return [
            'total' => $total,
            'vacant' => $vacant,
            'filled' => $filled
        ];
    }

    public function render()
    {
        // Build dependent filter options
        $this->buildFilterOptions();

        if ($this->isSuperadmin && !$this->selectedCompanyId) {
            return view('livewire.pages.vacant-positions.index', [
                'showCompanySelection' => true,
                'companies' => $this->companies,
                'isSuperadmin' => $this->isSuperadmin,
                'selectedCompany' => null,
                'positions' => collect(),
                'departments' => [],
                'bands' => [],
                'units' => [],
                'stats' => $this->getStats()
            ]);
        }

        $query = $this->getBaseQuery();

        // Filter search
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('position_code', 'like', '%' . $this->search . '%');
            });
        }

        // Filter department
        if (!empty($this->filterDepartment)) {
            $query->where('department', $this->filterDepartment);
        }

        // Filter unit - check all unit levels
        if (!empty($this->filterUnit)) {
            $query->where(function ($q) {
                $q->where('section', $this->filterUnit)
                    ->orWhere('biro', $this->filterUnit)
                    ->orWhere('department', $this->filterUnit)
                    ->orWhere('directorate', $this->filterUnit);
            });
        }

        // Filter band
        if (!empty($this->filterBand)) {
            $query->where('band', $this->filterBand);
        }

        $positions = $query->orderBy('band')->paginate(10);

        return view('livewire.pages.vacant-positions.index', [
            'showCompanySelection' => false,
            'positions' => $positions,
            'departments' => $this->departmentOptions,
            'bands' => $this->bandOptions,
            'units' => $this->unitOptions,
            'selectedCompany' => $this->selectedCompany,
            'isSuperadmin' => $this->isSuperadmin,
            'companies' => $this->companies,
            'stats' => $this->getStats(),
            'activeTab' => $this->activeTab
        ]);
    }
}
