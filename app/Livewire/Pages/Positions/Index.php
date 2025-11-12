<?php

namespace App\Livewire\Pages\Positions;

use App\Models\Position;
use App\Models\Company;
use App\Models\WorkUnit;
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

    #[Url(as: 'band')]
    public $bandFilter = '';

    #[Url(as: 'grade')]
    public $gradeFilter = '';

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
        $this->resetPage(); // Reset work unit filter when company changes
    }

    public function updatingWorkUnitFilter()
    {
        $this->resetPage();
    }

    public function updatingBandFilter()
    {
        $this->resetPage();
    }

    public function updatingGradeFilter()
    {
        $this->resetPage();
    }

    public function edit($positionId)
    {
        $position = Position::findOrFail($positionId);
        return $this->redirect(route('positions.edit', $position->slug), navigate: true);
    }

    // Implement abstract methods from trait
    protected function getModelClass(): string
    {
        return Position::class;
    }

    protected function getModelDisplayName(): string
    {
        return 'Position';
    }

    public function render()
    {
        $positions = Position::query()
            ->with(['company'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('position_code', 'like', '%' . $this->search . '%')
                        ->orWhere('band', 'like', '%' . $this->search . '%')
                        ->orWhere('job_grade', 'like', '%' . $this->search . '%')
                        ->orWhereHas('company', function ($companyQuery) {
                            $companyQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->companyFilter, function ($query) {
                $query->where('company_id', $this->companyFilter);
            })
            ->when($this->bandFilter, function ($query) {
                $query->where('band', $this->bandFilter);
            })
            ->when($this->gradeFilter, function ($query) {
                $query->where('job_grade', $this->gradeFilter);
            })
            ->when($this->sortBy === 'name_asc', fn($query) => $query->orderBy('name', 'asc'))
            ->when($this->sortBy === 'name_desc', fn($query) => $query->orderBy('name', 'desc'))
            ->when($this->sortBy === 'code_asc', fn($query) => $query->orderBy('position_code', 'asc'))
            ->when($this->sortBy === 'code_desc', fn($query) => $query->orderBy('position_code', 'desc'))
            ->when($this->sortBy === 'band_asc', fn($query) => $query->orderBy('band', 'asc'))
            ->when($this->sortBy === 'band_desc', fn($query) => $query->orderBy('band', 'desc'))
            ->when($this->sortBy === 'newest', fn($query) => $query->latest('created_at'))
            ->when($this->sortBy === 'oldest', fn($query) => $query->oldest('created_at'))
            ->paginate(10);

        $companies = Company::orderBy('name')->get();


        // Get unique bands and grades from existing positions
        $bands = Position::distinct()->pluck('band')->sort()->values();
        $grades = Position::distinct()->pluck('job_grade')->sort()->values();

        return view('livewire.pages.positions.index', [
            'positions' => $positions,
            'companies' => $companies,
            'bands' => $bands,
            'grades' => $grades,
        ]);
    }
}
