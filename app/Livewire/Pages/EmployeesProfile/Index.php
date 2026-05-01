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
    }

    public function changeCompany()
    {
        $this->selectedCompanyId = null;
        $this->selectedCompany = null;
        session()->forget('employees_profile_company_id');
        $this->resetPage();
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

    public function render()
    {
        // Jika superadmin belum pilih company
        if ($this->isSuperadmin && !$this->selectedCompanyId) {
            return view('livewire.pages.employees-profile.index', [
                'showCompanySelection' => true,
                'companies' => $this->companies,
                'isSuperadmin' => $this->isSuperadmin,
                'selectedCompany' => null,
                'employees' => collect(),
                'units' => collect(),
                'total' => 0
            ]);
        }

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

        // Get unique units untuk dropdown (menggunakan work_unit logic)
        $units = Position::where('company_id', $companyId)
            ->where(function ($query) {
                $query->whereNotNull('section')
                    ->orWhereNotNull('biro')
                    ->orWhereNotNull('department')
                    ->orWhereNotNull('directorate');
            })
            ->get()
            ->map(function ($position) {
                return $position->work_unit; // Pakai accessor work_unit dari model
            })
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $departments = Position::where('company_id', $companyId)
            ->whereNotNull('department')
            ->pluck('department')
            ->unique()
            ->sort()
            ->values();

        $bands = Employee::where('company_id', $companyId)
            ->whereNotNull('band')
            ->distinct()
            ->orderBy('band')
            ->pluck('band')
            ->toArray();

        return view('livewire.pages.employees-profile.index', [
            'showCompanySelection' => false,
            'employees' => $employees,
            'total' => $employees->total(),
            'units' => $units,
            'departments' => $departments,
            'bands' => $bands,
            'selectedCompany' => $selectedCompany,
            'isSuperadmin' => $this->isSuperadmin,
            'companies' => $this->companies
        ]);
    }
}
