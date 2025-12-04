<?php

namespace App\Livewire\Pages\TechnicalCompetencies;

use App\Models\TechnicalCompetency;
use App\Models\Employee;
use App\Models\Company;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Livewire\Traits\WithDeleteConfirmation;

#[Layout('components.layouts.app')]
#[Title('Technical Competencies')]
class Index extends Component
{
    use WithPagination, WithDeleteConfirmation;

    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'sort')]
    public $sortBy = 'year_desc';

    #[Url(as: 'company')]
    public $companyFilter = '';

    #[Url(as: 'employee')]
    public $employeeFilter = '';

    #[Url(as: 'year')]
    public $yearFilter = '';

    #[Url(as: 'issued_by')]
    public $issuedByFilter = '';

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

    public function updatingYearFilter()
    {
        $this->resetPage();
    }

    public function updatingIssuedByFilter()
    {
        $this->resetPage();
    }

    public function edit($competencyId)
    {
        $competency = TechnicalCompetency::findOrFail($competencyId);
        return $this->redirect(route('technical-competencies.edit', $competency->id), navigate: true);
    }

    protected function getModelClass(): string
    {
        return TechnicalCompetency::class;
    }

    protected function getModelDisplayName(): string
    {
        return 'Technical Competency';
    }

    protected function performDelete($competency): void
    {
        $competency->delete();
    }

    public function render()
    {
        $competencies = TechnicalCompetency::query()
            ->with(['employee.company'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('issued_by', 'like', '%' . $this->search . '%')
                        ->orWhereHas('employee', function ($employeeQuery) {
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
            ->when($this->yearFilter, fn($query) => $query->where('year', $this->yearFilter))
            ->when($this->issuedByFilter, fn($query) => $query->where('issued_by', 'like', '%' . $this->issuedByFilter . '%'))
            ->when($this->sortBy === 'employee_name_asc', fn($query) => $query->join('employees', 'technical_competencies.employee_id', '=', 'employees.id')
                ->orderBy('employees.name', 'asc')
                ->select('technical_competencies.*'))
            ->when($this->sortBy === 'employee_name_desc', fn($query) => $query->join('employees', 'technical_competencies.employee_id', '=', 'employees.id')
                ->orderBy('employees.name', 'desc')
                ->select('technical_competencies.*'))
            ->when($this->sortBy === 'name_asc', fn($query) => $query->orderBy('name', 'asc'))
            ->when($this->sortBy === 'name_desc', fn($query) => $query->orderBy('name', 'desc'))
            ->when($this->sortBy === 'year_desc', fn($query) => $query->orderBy('year', 'desc'))
            ->when($this->sortBy === 'year_asc', fn($query) => $query->orderBy('year', 'asc'))
            ->when($this->sortBy === 'newest', fn($query) => $query->latest('created_at'))
            ->when($this->sortBy === 'oldest', fn($query) => $query->oldest('created_at'))
            ->paginate(10);

        $companies = Company::orderBy('name')->get();

        $employees = $this->companyFilter
            ? Employee::where('company_id', $this->companyFilter)->orderBy('name')->get()
            : Employee::orderBy('name')->get();

        // Get unique years for filter
        $years = TechnicalCompetency::select('year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->get()
            ->pluck('year');

        // Get unique issued_by for filter
        $issuedBys = TechnicalCompetency::select('issued_by')
            ->distinct()
            ->orderBy('issued_by')
            ->get()
            ->pluck('issued_by');

        return view('livewire.pages.technical-competencies.index', [
            'competencies' => $competencies,
            'companies' => $companies,
            'employees' => $employees,
            'years' => $years,
            'issuedBys' => $issuedBys,
        ]);
    }
}
