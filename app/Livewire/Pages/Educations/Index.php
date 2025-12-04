<?php

namespace App\Livewire\Pages\Educations;

use App\Models\Education;
use App\Models\Employee;
use App\Models\Company;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Livewire\Traits\WithDeleteConfirmation;

#[Layout('components.layouts.app')]
#[Title('Education')]
class Index extends Component
{
    use WithPagination, WithDeleteConfirmation;

    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'sort')]
    public $sortBy = 'employee_name_asc';

    #[Url(as: 'company')]
    public $companyFilter = '';

    #[Url(as: 'employee')]
    public $employeeFilter = '';

    #[Url(as: 'degree')]
    public $degreeFilter = '';

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
        $this->employeeFilter = '';
    }

    public function updatingEmployeeFilter()
    {
        $this->resetPage();
    }

    public function updatingDegreeFilter()
    {
        $this->resetPage();
    }

    public function edit($educationId)
    {
        $education = Education::findOrFail($educationId);
        return $this->redirect(route('educations.edit', $education->id), navigate: true);
    }

    protected function getModelClass(): string
    {
        return Education::class;
    }

    protected function getModelDisplayName(): string
    {
        return 'Education';
    }

    protected function performDelete($education): void
    {
        $education->delete();
    }

    public function render()
    {
        $education = Education::query()
            ->with(['employee.company'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('employee', function ($employeeQuery) {
                        $employeeQuery->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('employee_number', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->when($this->companyFilter, function ($query) {
                $query->whereHas('employee', function ($employeeQuery) {
                    $employeeQuery->where('company_id', $this->companyFilter);
                });
            })
            ->when($this->employeeFilter, fn($query) => $query->where('employee_id', $this->employeeFilter))
            ->when($this->degreeFilter, fn($query) => $query->where('degree', $this->degreeFilter))
            ->when($this->sortBy === 'employee_name_asc', fn($query) => $query->join('employees', 'education.employee_id', '=', 'employees.id')
                ->orderBy('employees.name', 'asc')
                ->select('education.*'))
            ->when($this->sortBy === 'employee_name_desc', fn($query) => $query->join('employees', 'education.employee_id', '=', 'employees.id')
                ->orderBy('employees.name', 'desc')
                ->select('education.*'))
            ->when($this->sortBy === 'institution_asc', fn($query) => $query->orderBy('institution_name', 'asc'))
            ->when($this->sortBy === 'institution_desc', fn($query) => $query->orderBy('institution_name', 'desc'))
            ->when($this->sortBy === 'start_date_desc', fn($query) => $query->orderBy('start_date', 'desc'))
            ->when($this->sortBy === 'start_date_asc', fn($query) => $query->orderBy('start_date', 'asc'))
            ->when($this->sortBy === 'newest', fn($query) => $query->latest('education.created_at'))
            ->when($this->sortBy === 'oldest', fn($query) => $query->oldest('education.created_at'))
            ->paginate(10);

        $companies = Company::orderBy('name')->get();

        $employees = $this->companyFilter
            ? Employee::where('company_id', $this->companyFilter)->orderBy('name')->get()
            : Employee::orderBy('name')->get();

        // Get unique degrees for filter
        $degrees = Education::select('degree')
            ->distinct()
            ->orderBy('degree')
            ->get()
            ->pluck('degree');

        return view('livewire.pages.educations.index', [
            'education' => $education,
            'companies' => $companies,
            'employees' => $employees,
            'degrees' => $degrees,
        ]);
    }
}
