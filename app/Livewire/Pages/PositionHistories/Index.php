<?php

namespace App\Livewire\Pages\PositionHistories;

use App\Models\PositionHistory;
use App\Models\Employee;
use App\Models\Company as CompanyModel;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Livewire\Traits\WithDeleteConfirmation;

#[Layout('components.layouts.app')]
#[Title('Position Histories')]
class Index extends Component
{
    use WithPagination, WithDeleteConfirmation;

    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'sort')]
    public $sortBy = 'start_date_desc';

    #[Url(as: 'company')]
    public $companyFilter = '';

    #[Url(as: 'employee')]
    public $employeeFilter = '';

    #[Url(as: 'work_unit')]
    public $workUnitFilter = '';

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

    public function updatingWorkUnitFilter()
    {
        $this->resetPage();
    }

    public function edit($historyId)
    {
        $history = PositionHistory::findOrFail($historyId);
        return $this->redirect(route('position-histories.edit', $history->id), navigate: true);
    }

    protected function getModelClass(): string
    {
        return PositionHistory::class;
    }

    protected function getModelDisplayName(): string
    {
        return 'Position History';
    }

    protected function performDelete($history): void
    {
        $history->delete();
    }

    public function render()
    {
        $histories = PositionHistory::query()
            ->with(['employee'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('position_name', 'like', '%' . $this->search . '%')
                        ->orWhere('work_unit', 'like', '%' . $this->search . '%')
                        ->orWhere('company', 'like', '%' . $this->search . '%')
                        ->orWhereHas('employee', function ($employeeQuery) {
                            $employeeQuery->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('employee_number', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->companyFilter, fn($query) => $query->where('company', 'like', '%' . $this->companyFilter . '%'))
            ->when($this->employeeFilter, fn($query) => $query->where('employee_id', $this->employeeFilter))
            ->when($this->workUnitFilter, fn($query) => $query->where('work_unit', 'like', '%' . $this->workUnitFilter . '%'))
            ->when($this->sortBy === 'employee_name_asc', fn($query) => $query->join('employees', 'position_histories.employee_id', '=', 'employees.id')
                ->orderBy('employees.name', 'asc')
                ->select('position_histories.*'))
            ->when($this->sortBy === 'employee_name_desc', fn($query) => $query->join('employees', 'position_histories.employee_id', '=', 'employees.id')
                ->orderBy('employees.name', 'desc')
                ->select('position_histories.*'))
            ->when($this->sortBy === 'start_date_desc', fn($query) => $query->orderBy('start_date', 'desc'))
            ->when($this->sortBy === 'start_date_asc', fn($query) => $query->orderBy('start_date', 'asc'))
            ->when($this->sortBy === 'end_date_desc', fn($query) => $query->orderBy('end_date', 'desc'))
            ->when($this->sortBy === 'end_date_asc', fn($query) => $query->orderBy('end_date', 'asc'))
            ->when($this->sortBy === 'newest', fn($query) => $query->latest('created_at'))
            ->when($this->sortBy === 'oldest', fn($query) => $query->oldest('created_at'))
            ->paginate(10);

        $companies = PositionHistory::select('company')
            ->distinct()
            ->orderBy('company')
            ->get()
            ->pluck('company')
            ->filter();

        $allCompanies = CompanyModel::orderBy('name')->get();

        $employees = $this->companyFilter
            ? Employee::orderBy('name')->get() // Note: company di sini adalah field string, bukan relation
            : Employee::orderBy('name')->get();

        // Get unique work units for filter
        $workUnits = PositionHistory::select('work_unit')
            ->distinct()
            ->orderBy('work_unit')
            ->get()
            ->pluck('work_unit')
            ->filter();

        return view('livewire.pages.position-histories.index', [
            'histories' => $histories,
            'companies' => $companies,
            'allCompanies' => $allCompanies,
            'employees' => $employees,
            'workUnits' => $workUnits,
        ]);
    }
}
