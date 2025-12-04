<?php

namespace App\Livewire\Pages\Mcus;

use App\Models\Mcu;
use App\Models\Employee;
use App\Models\Company;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Livewire\Traits\WithDeleteConfirmation;

#[Layout('components.layouts.app')]
#[Title('Medical Check Up')]
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

    #[Url(as: 'result')]
    public $resultFilter = '';

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

    public function updatingResultFilter()
    {
        $this->resetPage();
    }

    public function edit($mcuId)
    {
        $mcu = Mcu::findOrFail($mcuId);
        return $this->redirect(route('mcus.edit', $mcu->id), navigate: true);
    }

    protected function getModelClass(): string
    {
        return Mcu::class;
    }

    protected function getModelDisplayName(): string
    {
        return 'Medical Check Up Record';
    }

    protected function performDelete($mcu): void
    {
        $mcu->delete();
    }

    public function render()
    {
        $mcus = Mcu::query()
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
            ->when($this->yearFilter, fn($query) => $query->where('year', $this->yearFilter))
            ->when($this->resultFilter, fn($query) => $query->where('result', $this->resultFilter))
            ->when($this->sortBy === 'employee_name_asc', fn($query) => $query->join('employees', 'mcus.employee_id', '=', 'employees.id')
                ->orderBy('employees.name', 'asc')
                ->select('mcus.*'))
            ->when($this->sortBy === 'employee_name_desc', fn($query) => $query->join('employees', 'mcus.employee_id', '=', 'employees.id')
                ->orderBy('employees.name', 'desc')
                ->select('mcus.*'))
            ->when($this->sortBy === 'year_asc', fn($query) => $query->orderBy('year', 'asc'))
            ->when($this->sortBy === 'year_desc', fn($query) => $query->orderBy('year', 'desc'))
            ->when($this->sortBy === 'newest', fn($query) => $query->latest('created_at'))
            ->when($this->sortBy === 'oldest', fn($query) => $query->oldest('created_at'))
            ->paginate(10);

        $companies = Company::orderBy('name')->get();

        $employees = $this->companyFilter
            ? Employee::where('company_id', $this->companyFilter)->orderBy('name')->get()
            : Employee::orderBy('name')->get();

        // Get unique years for filter
        $years = Mcu::select('year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->get()
            ->pluck('year');

        // Result options K1-K5
        $resultOptions = ['K1', 'K2', 'K3', 'K4', 'K5'];

        return view('livewire.pages.mcus.index', [
            'mcus' => $mcus,
            'companies' => $companies,
            'employees' => $employees,
            'years' => $years,
            'resultOptions' => $resultOptions,
        ]);
    }
}
