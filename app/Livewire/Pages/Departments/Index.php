<?php

namespace App\Livewire\Pages\Departments;

use App\Models\Departemen;
use App\Models\Company;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Livewire\Traits\WithDeleteConfirmation;

#[Layout('components.layouts.app')]
#[Title('Departments')]
class Index extends Component
{
    use WithPagination, WithDeleteConfirmation;

    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'sort')]
    public $sortBy = 'name_asc';

    #[Url(as: 'company')]
    public $companyFilter = '';

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
    }

    public function edit($departmentId)
    {
        $department = Departemen::findOrFail($departmentId);
        return $this->redirect(route('departments.edit', $department->slug), navigate: true);
    }

    // Implement abstract methods from trait
    protected function getModelClass(): string
    {
        return Departemen::class;
    }

    protected function getModelDisplayName(): string
    {
        return 'Department';
    }

    public function render()
    {
        $departments = Departemen::query()
            ->with('company')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhereHas('company', function ($companyQuery) {
                            $companyQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->companyFilter, function ($query) {
                $query->where('company_id', $this->companyFilter);
            })
            ->when($this->sortBy === 'name_asc', fn($query) => $query->orderBy('name', 'asc'))
            ->when($this->sortBy === 'name_desc', fn($query) => $query->orderBy('name', 'desc'))
            ->when($this->sortBy === 'newest', fn($query) => $query->latest('created_at'))
            ->when($this->sortBy === 'oldest', fn($query) => $query->oldest('created_at'))
            ->paginate(10);

        $companies = Company::orderBy('name')->get();

        return view('livewire.pages.departments.index', [
            'departments' => $departments,
            'companies' => $companies,
        ]);
    }
}
