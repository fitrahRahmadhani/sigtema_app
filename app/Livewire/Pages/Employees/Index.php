<?php

namespace App\Livewire\Pages\Employees;

use App\Models\Employee;
use App\Models\Company;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Livewire\Traits\WithDeleteConfirmation;

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
    }

    public function changeCompany()
    {
        $this->selectedCompanyId = null;
        $this->selectedCompany = null;
        session()->forget('employees_company_id');
        $this->resetPage();
    }

    public function updatingSearch()
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
        if ($employee->profile_path && Storage::disk('public')->exists($employee->profile_path)) {
            Storage::disk('public')->delete($employee->profile_path);
        }

        $employee->delete();
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
            ->orderBy('name', 'asc')
            ->paginate(10);

        return view('livewire.pages.employees.index', [
            'showCompanySelection' => false,
            'employees' => $employees,
            'selectedCompany' => $this->selectedCompany,
            'isSuperadmin' => $this->isSuperadmin,
            'companies' => $this->companies,
        ]);
    }
}
