<?php

namespace App\Livewire\Pages\PsychogramResults;

use App\Models\PsychogramResult;
use App\Models\Employee;
use App\Models\Company;
use App\Models\PsychogramAspect;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Livewire\Traits\WithDeleteConfirmation;

#[Layout('components.layouts.app')]
#[Title('Psychogram Results')]
class Index extends Component
{
    use WithPagination, WithDeleteConfirmation;

    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'company')]
    public $companyFilter = '';

    #[Url(as: 'employee')]
    public $employeeFilter = '';

    #[Url(as: 'aspect')]
    public $aspectFilter = '';

    #[Url(as: 'score_min')]
    public $scoreMin = '';

    #[Url(as: 'score_max')]
    public $scoreMax = '';

    #[Url(as: 'sort')]
    public $sortBy = 'created_desc';

    public function updatingSearch()
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

    public function updatingAspectFilter()
    {
        $this->resetPage();
    }

    public function updatingScoreMin()
    {
        $this->resetPage();
    }

    public function updatingScoreMax()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->companyFilter = '';
        $this->employeeFilter = '';
        $this->aspectFilter = '';
        $this->scoreMin = '';
        $this->scoreMax = '';
        $this->sortBy = 'created_desc';
        $this->resetPage();
    }

    public function edit($resultId)
    {
        $result = PsychogramResult::findOrFail($resultId);
        return $this->redirect(route('psychogram-results.edit', $result), navigate: true);
    }

    public function view($resultId)
    {
        $result = PsychogramResult::findOrFail($resultId);
        return $this->redirect(route('psychogram-results.show', $result), navigate: true);
    }

    protected function getModelClass(): string
    {
        return PsychogramResult::class;
    }

    protected function getModelDisplayName(): string
    {
        return 'Psychogram Result';
    }

    protected function performDelete($result): void
    {
        $result->delete();
    }

    public function render()
    {
        $results = PsychogramResult::query()
            ->with(['employee.company', 'aspect'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('employee', function ($employeeQuery) {
                        $employeeQuery->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('employee_number', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('aspect', function ($aspectQuery) {
                        $aspectQuery->where('aspect_code', 'like', '%' . $this->search . '%')
                            ->orWhere('aspect_name', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->when($this->companyFilter, function ($query) {
                $query->whereHas('employee', function ($employeeQuery) {
                    $employeeQuery->where('company_id', $this->companyFilter);
                });
            })
            ->when($this->employeeFilter, fn($query) => $query->where('employee_id', $this->employeeFilter))
            ->when($this->aspectFilter, fn($query) => $query->where('aspect_id', $this->aspectFilter))
            ->when($this->scoreMin, fn($query) => $query->where('score', '>=', $this->scoreMin))
            ->when($this->scoreMax, fn($query) => $query->where('score', '<=', $this->scoreMax))
            ->when($this->sortBy === 'created_desc', fn($query) => $query->latest('created_at'))
            ->when($this->sortBy === 'created_asc', fn($query) => $query->oldest('created_at'))
            ->when($this->sortBy === 'score_desc', fn($query) => $query->orderBy('score', 'desc'))
            ->when($this->sortBy === 'score_asc', fn($query) => $query->orderBy('score', 'asc'))
            ->when($this->sortBy === 'employee_name_asc', fn($query) => $query->join('employees', 'psychogram_results.employee_id', '=', 'employees.id')->orderBy('employees.name', 'asc'))
            ->when($this->sortBy === 'employee_name_desc', fn($query) => $query->join('employees', 'psychogram_results.employee_id', '=', 'employees.id')->orderBy('employees.name', 'desc'))
            ->when($this->sortBy === 'aspect_name_asc', fn($query) => $query->join('psychogram_aspects', 'psychogram_results.aspect_id', '=', 'psychogram_aspects.id')->orderBy('psychogram_aspects.aspect_name', 'asc'))
            ->when($this->sortBy === 'aspect_name_desc', fn($query) => $query->join('psychogram_aspects', 'psychogram_results.aspect_id', '=', 'psychogram_aspects.id')->orderBy('psychogram_aspects.aspect_name', 'desc'))
            ->paginate(15);

        $companies = Company::orderBy('name')->get();
        
        $employees = $this->companyFilter 
            ? Employee::where('company_id', $this->companyFilter)->orderBy('name')->get()
            : Employee::orderBy('name')->get();
            
        $aspects = PsychogramAspect::where('is_active', true)->orderBy('aspect_name')->get();

        return view('livewire.pages.psychogram-results.index', [
            'results' => $results,
            'companies' => $companies,
            'employees' => $employees,
            'aspects' => $aspects,
        ]);
    }
}