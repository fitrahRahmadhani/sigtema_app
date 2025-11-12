<?php

namespace App\Livewire\Pages\Positions;

use App\Models\Position;
use App\Models\Company;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Livewire\Traits\WithDeleteConfirmation;

#[Layout('components.layouts.app')]
#[Title('Positions')]
class Index extends Component
{
    use WithPagination, WithDeleteConfirmation;

    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'sort')]
    public $sortBy = 'name_asc';

    #[Url(as: 'company')]
    public $companyFilter = '';

    #[Url(as: 'grade')]
    public $gradeFilter = '';

    #[Url(as: 'department')]
    public $departmentFilter = '';

    #[Url(as: 'location')]
    public $locationFilter = '';

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

    public function updatingGradeFilter()
    {
        $this->resetPage();
    }

    public function updatingDepartmentFilter()
    {
        $this->resetPage();
    }

    public function updatingLocationFilter()
    {
        $this->resetPage();
    }

    public function edit($positionId)
    {
        $position = Position::findOrFail($positionId);
        return $this->redirect(route('positions.edit', $position->slug), navigate: true);
    }

    protected function getModelClass(): string
    {
        return Position::class;
    }

    protected function getModelDisplayName(): string
    {
        return 'Position';
    }

    protected function performDelete($position): void
    {
        $position->delete();
    }

    public function render()
    {
        $positions = Position::query()
            ->with(['company'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('position_code', 'like', '%' . $this->search . '%')
                        ->orWhere('job_grade', 'like', '%' . $this->search . '%')
                        ->orWhere('directorate', 'like', '%' . $this->search . '%')
                        ->orWhere('department', 'like', '%' . $this->search . '%')
                        ->orWhere('biro', 'like', '%' . $this->search . '%')
                        ->orWhere('section', 'like', '%' . $this->search . '%')
                        ->orWhere('location', 'like', '%' . $this->search . '%')
                        ->orWhereHas('company', function ($companyQuery) {
                            $companyQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->companyFilter, fn($query) => $query->where('company_id', $this->companyFilter))
            ->when($this->gradeFilter, fn($query) => $query->where('job_grade', $this->gradeFilter))
            ->when($this->departmentFilter, fn($query) => $query->where('department', $this->departmentFilter))
            ->when($this->locationFilter, fn($query) => $query->where('location', $this->locationFilter))
            ->when($this->sortBy === 'name_asc', fn($query) => $query->orderBy('name', 'asc'))
            ->when($this->sortBy === 'name_desc', fn($query) => $query->orderBy('name', 'desc'))
            ->when($this->sortBy === 'code_asc', fn($query) => $query->orderBy('position_code', 'asc'))
            ->when($this->sortBy === 'code_desc', fn($query) => $query->orderBy('position_code', 'desc'))
            ->when($this->sortBy === 'grade_asc', fn($query) => $query->orderBy('job_grade', 'asc'))
            ->when($this->sortBy === 'grade_desc', fn($query) => $query->orderBy('job_grade', 'desc'))
            ->when($this->sortBy === 'newest', fn($query) => $query->latest('created_at'))
            ->when($this->sortBy === 'oldest', fn($query) => $query->oldest('created_at'))
            ->paginate(10);

        $companies = Company::orderBy('name')->get();

        // Get unique values for filters
        $grades = Position::distinct()
            ->whereNotNull('job_grade')
            ->orderBy('job_grade')
            ->pluck('job_grade')
            ->toArray();

        $departments = Position::distinct()
            ->whereNotNull('department')
            ->orderBy('department')
            ->pluck('department')
            ->toArray();

        $locations = Position::distinct()
            ->whereNotNull('location')
            ->orderBy('location')
            ->pluck('location')
            ->toArray();

        return view('livewire.pages.positions.index', [
            'positions' => $positions,
            'companies' => $companies,
            'grades' => $grades,
            'departments' => $departments,
            'locations' => $locations,
        ]);
    }
}
