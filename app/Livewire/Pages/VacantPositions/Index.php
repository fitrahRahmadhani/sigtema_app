<?php

namespace App\Livewire\Pages\VacantPositions;

use App\Models\Company;
use Livewire\Component;
use App\Models\Position;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

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

    private function resetFilters()
    {
        $this->search = '';
        $this->filterDepartment = '';
        $this->filterUnit = '';
        $this->filterBand = '';
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterDepartment(): void
    {
        $this->resetPage();
    }

    public function updatedFilterUnit(): void
    {
        $this->resetPage();
    }

    public function updatedFilterBand(): void
    {
        $this->resetPage();
    }

    public function updatedActiveTab(): void
    {
        $this->resetPage();
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
        if ($this->isSuperadmin && !$this->selectedCompanyId) {
            return view('livewire.pages.vacant-positions.index', [
                'showCompanySelection' => true,
                'companies' => $this->companies,
                'isSuperadmin' => $this->isSuperadmin,
                'selectedCompany' => null,
                'positions' => collect(),
                'departments' => collect(),
                'units' => collect(),
                'stats' => $this->getStats()
            ]);
        }

        $query = Position::where('company_id', $this->selectedCompanyId);

        // Filter berdasarkan tab aktif
        if ($this->activeTab === 'vacant') {
            $query->whereNull('employee_id');
        }

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

        // Get unique departments and units for filters
        $departmentQuery = Position::where('company_id', $this->selectedCompanyId);
        $unitQuery = Position::where('company_id', $this->selectedCompanyId);

        if ($this->activeTab === 'vacant') {
            $departmentQuery->whereNull('employee_id');
            $unitQuery->whereNull('employee_id');
        }

        $departments = $departmentQuery->whereNotNull('department')
            ->pluck('department')
            ->unique()
            ->sort()
            ->values();

        $bands = Position::where('company_id', $this->selectedCompanyId)
            ->distinct()
            ->whereNotNull('band')
            ->orderBy('band')
            ->pluck('band')
            ->toArray();

        $units = $unitQuery->where(function ($q) {
            $q->whereNotNull('section')
                ->orWhereNotNull('biro')
                ->orWhereNotNull('department')
                ->orWhereNotNull('directorate');
        })
            ->get()
            ->map(function ($position) {
                return $position->work_unit;
            })
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return view('livewire.pages.vacant-positions.index', [
            'showCompanySelection' => false,
            'positions' => $positions,
            'departments' => $departments,
            'bands' => $bands,
            'units' => $units,
            'selectedCompany' => $this->selectedCompany,
            'isSuperadmin' => $this->isSuperadmin,
            'companies' => $this->companies,
            'stats' => $this->getStats(),
            'activeTab' => $this->activeTab
        ]);
    }
}
