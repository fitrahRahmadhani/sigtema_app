<?php

namespace App\Livewire\Pages\Employees;

use App\Livewire\Traits\WithDeleteConfirmation;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Employees')]
class Index extends Component
{
    use WithPagination, WithDeleteConfirmation;

    public $selectedCompanyId = null;
    public $selectedCompany = null;
    public $companies = [];
    public $isSuperadmin = false;

    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'sort')]
    public $sortBy = 'name_asc';

    #[Url(as: 'band')]
    public $bandFilter = '';

    public function mount(): void
    {
        $user = Auth::user();

        $this->isSuperadmin = $user->hasRole('superadmin');

        if ($this->isSuperadmin) {
            $this->companies = Company::where('is_active', true)->get();
            $this->selectedCompanyId = session('employees_company_id') ?? request('company_id');

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
        session(['employees_company_id' => $companyId]);
        $this->resetPage();
        $this->resetFilters();
    }

    public function changeCompany()
    {
        $this->selectedCompanyId = null;
        $this->selectedCompany = null;
        session()->forget('employees_company_id');
        $this->resetPage();
        $this->resetFilters();
    }

    private function resetFilters()
    {
        $this->search = '';
        $this->sortBy = 'name_asc';
        $this->bandFilter = '';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function updatingBandFilter()
    {
        $this->resetPage();
    }

    public function show($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        return $this->redirect(route('employees.show', $employee->slug), navigate: true);
    }

    public function edit($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        return $this->redirect(route('employees.edit', $employee->slug), navigate: true);
    }

    protected function getModelClass(): string
    {
        return Employee::class;
    }

    protected function getModelDisplayName(): string
    {
        return 'Employee';
    }

    protected function performDelete($employee): void
    {

        $this->authorize('delete', [Employee::class, $employee]);

        DB::beginTransaction();

        try {
            $profilePath = $employee->profile_path;

            // Delete the employee from database
            $employee->delete();

            // Delete profile picture jika ada
            if ($profilePath && Storage::disk('public')->exists($profilePath)) {
                Storage::disk('public')->delete($profilePath);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->handleException('delete employee', $e->getMessage());
        }
    }

    public function render()
    {
        // Jika superadmin belum pilih company
        if ($this->isSuperadmin && !$this->selectedCompanyId) {
            return view('livewire.pages.employees.index', [
                'showCompanySelection' => true,
                'companies' => $this->companies,
                'isSuperadmin' => $this->isSuperadmin,
                'selectedCompany' => null,
                'employees' => collect(),
                'bands' => [],
            ]);
        }

        $employees = Employee::query()
            ->with(['company', 'position'])
            ->where('company_id', $this->selectedCompanyId)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('employee_number', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->bandFilter, fn($query) => $query->where('band', $this->bandFilter))
            ->when($this->sortBy === 'name_asc', fn($query) => $query->orderBy('name', 'asc'))
            ->when($this->sortBy === 'name_desc', fn($query) => $query->orderBy('name', 'desc'))
            ->when($this->sortBy === 'number_asc', fn($query) => $query->orderBy('employee_number', 'asc'))
            ->when($this->sortBy === 'number_desc', fn($query) => $query->orderBy('employee_number', 'desc'))
            ->when($this->sortBy === 'newest', fn($query) => $query->latest('created_at'))
            ->when($this->sortBy === 'oldest', fn($query) => $query->oldest('created_at'))
            ->paginate(10);

        // Get unique bands from employees for the selected company
        $bands = Employee::where('company_id', $this->selectedCompanyId)
            ->distinct()
            ->whereNotNull('band')
            ->orderBy('band')
            ->pluck('band')
            ->toArray();

        return view('livewire.pages.employees.index', [
            'showCompanySelection' => false,
            'employees' => $employees,
            'selectedCompany' => $this->selectedCompany,
            'isSuperadmin' => $this->isSuperadmin,
            'companies' => $this->companies,
            'bands' => $bands,
        ]);
    }
}
