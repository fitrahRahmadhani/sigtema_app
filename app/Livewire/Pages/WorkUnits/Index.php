<?php

namespace App\Livewire\Pages\WorkUnits;

use App\Models\WorkUnit;
use App\Models\Company;
use App\Models\Departemen;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Livewire\Traits\WithDeleteConfirmation;

#[Layout('components.layouts.app')]
#[Title('Work Units')]
class Index extends Component
{
    use WithPagination, WithDeleteConfirmation;

    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'sort')]
    public $sortBy = 'name_asc';

    #[Url(as: 'company')]
    public $companyFilter = '';

    #[Url(as: 'department')]
    public $departmentFilter = '';

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
        $this->departmentFilter = ''; // Reset department filter when company changes
    }

    public function updatingDepartmentFilter()
    {
        $this->resetPage();
    }

    public function edit($workUnitId)
    {
        $workUnit = WorkUnit::findOrFail($workUnitId);
        return $this->redirect(route('work-units.edit', $workUnit->slug), navigate: true);
    }

    // Implement abstract methods from trait
    protected function getModelClass(): string
    {
        return WorkUnit::class;
    }

    protected function getModelDisplayName(): string
    {
        return 'Work Unit';
    }

    public function render()
    {
        $workUnits = WorkUnit::query()
            ->with(['company', 'departemen'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('work_unit_code', 'like', '%' . $this->search . '%')
                        ->orWhereHas('company', function ($companyQuery) {
                            $companyQuery->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('departemen', function ($deptQuery) {
                            $deptQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->companyFilter, function ($query) {
                $query->where('company_id', $this->companyFilter);
            })
            ->when($this->departmentFilter, function ($query) {
                $query->where('departemen_id', $this->departmentFilter);
            })
            ->when($this->sortBy === 'name_asc', fn($query) => $query->orderBy('name', 'asc'))
            ->when($this->sortBy === 'name_desc', fn($query) => $query->orderBy('name', 'desc'))
            ->when($this->sortBy === 'code_asc', fn($query) => $query->orderBy('work_unit_code', 'asc'))
            ->when($this->sortBy === 'code_desc', fn($query) => $query->orderBy('work_unit_code', 'desc'))
            ->when($this->sortBy === 'newest', fn($query) => $query->latest('created_at'))
            ->when($this->sortBy === 'oldest', fn($query) => $query->oldest('created_at'))
            ->paginate(10);

        $companies = Company::orderBy('name')->get();

        // Get departments based on company filter
        $departments = $this->companyFilter
            ? Departemen::where('company_id', $this->companyFilter)->orderBy('name')->get()
            : Departemen::orderBy('name')->get();

        return view('livewire.pages.work-units.index', [
            'workUnits' => $workUnits,
            'companies' => $companies,
            'departments' => $departments,
        ]);
    }
}
