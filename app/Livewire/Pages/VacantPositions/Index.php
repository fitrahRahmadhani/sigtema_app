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

    protected $queryString = ['search', 'filterDepartment', 'filterUnit'];

    public function mount(): void
    {
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

    private function resetFilters()
    {
        $this->search = '';
        $this->filterDepartment = '';
        $this->filterUnit = '';
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

    public function getFilteredPositions()
    {
        if (!$this->selectedCompanyId) {
            return collect();
        }

        $query = Position::where('company_id', $this->selectedCompanyId)
            ->whereNull('employee_id');

        // Filter search
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('code', 'like', '%' . $this->search . '%');
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

        return $query->get();
    }

    public function getUniqueDepartments()
    {
        if (!$this->selectedCompanyId) {
            return collect();
        }

        return Position::where('company_id', $this->selectedCompanyId)
            ->whereNull('employee_id')
            ->whereNotNull('department')
            ->pluck('department')
            ->unique()
            ->sort()
            ->values();
    }

    public function getUniqueUnits()
    {
        if (!$this->selectedCompanyId) {
            return collect();
        }

        return Position::where('company_id', $this->selectedCompanyId)
            ->whereNull('employee_id')
            ->where(function ($query) {
                $query->whereNotNull('section')
                    ->orWhereNotNull('biro')
                    ->orWhereNotNull('department')
                    ->orWhereNotNull('directorate');
            })
            ->get()
            ->map(function ($position) {
                return $position->work_unit; // Using work_unit accessor
            })
            ->filter()
            ->unique()
            ->sort()
            ->values();
    }

    public function render()
    {
        // Jika superadmin belum pilih company
        if ($this->isSuperadmin && !$this->selectedCompanyId) {
            return view('livewire.pages.vacant-positions.index', [
                'showCompanySelection' => true,
                'companies' => $this->companies,
                'isSuperadmin' => $this->isSuperadmin,
                'selectedCompany' => null,
                'positions' => collect(),
                'total' => 0,
                'departments' => collect(),
                'units' => collect(),
            ]);
        }

        $filtered = $this->getFilteredPositions();
        $currentPage = request()->query('page', 1);
        $perPage = 10;

        $positions = $filtered->forPage($currentPage, $perPage);
        $total = $filtered->count();

        return view('livewire.pages.vacant-positions.index', [
            'showCompanySelection' => false,
            'positions' => $positions,
            'total' => $total,
            'departments' => $this->getUniqueDepartments(),
            'units' => $this->getUniqueUnits(),
            'selectedCompany' => $this->selectedCompany,
            'isSuperadmin' => $this->isSuperadmin,
            'companies' => $this->companies,
        ]);
    }
}
