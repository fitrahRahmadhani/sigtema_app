<?php

namespace App\Livewire\Pages\Employees;

use App\Models\Employee;
use App\Models\Company;
use App\Models\Position;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;
use App\Livewire\Traits\WithDeleteConfirmation;

#[Layout('components.layouts.app')]
#[Title('Employees')]
class Index extends Component
{
    use WithPagination, WithDeleteConfirmation;

    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'sort')]
    public $sortBy = 'name_asc';

    #[Url(as: 'company')]
    public $companyFilter = '';

    #[Url(as: 'position')]
    public $positionFilter = '';

    #[Url(as: 'gender')]
    public $genderFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function updatingCompanyFilter()
    {
        $this->resetPage();
        $this->positionFilter = '';
    }

    public function updatingPositionFilter()
    {
        $this->resetPage();
    }

    public function updatingGenderFilter()
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
        $employees = Employee::query()
            ->with(['company', 'position.workUnit.departemen'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('employee_number', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone', 'like', '%' . $this->search . '%')
                        ->orWhereHas('company', function ($companyQuery) {
                            $companyQuery->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('position', function ($posQuery) {
                            $posQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->companyFilter, fn($query) => $query->where('company_id', $this->companyFilter))
            ->when($this->positionFilter, fn($query) => $query->where('position_id', $this->positionFilter))
            ->when($this->genderFilter, fn($query) => $query->where('gender', $this->genderFilter))
            ->when($this->sortBy === 'name_asc', fn($query) => $query->orderBy('name', 'asc'))
            ->when($this->sortBy === 'name_desc', fn($query) => $query->orderBy('name', 'desc'))
            ->when($this->sortBy === 'employee_number_asc', fn($query) => $query->orderBy('employee_number', 'asc'))
            ->when($this->sortBy === 'employee_number_desc', fn($query) => $query->orderBy('employee_number', 'desc'))
            ->when($this->sortBy === 'hire_date_newest', fn($query) => $query->orderBy('hire_date', 'desc'))
            ->when($this->sortBy === 'hire_date_oldest', fn($query) => $query->orderBy('hire_date', 'asc'))
            ->when($this->sortBy === 'newest', fn($query) => $query->latest('created_at'))
            ->when($this->sortBy === 'oldest', fn($query) => $query->oldest('created_at'))
            ->paginate(10);

        $companies = Company::orderBy('name')->get();

        $positions = $this->companyFilter
            ? Position::where('company_id', $this->companyFilter)->orderBy('name')->get()
            : Position::orderBy('name')->get();

        return view('livewire.pages.employees.index', [
            'employees' => $employees,
            'companies' => $companies,
            'positions' => $positions,
        ]);
    }
}
