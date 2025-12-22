<?php

namespace App\Livewire\Pages\Positions;

use App\Models\Position;
use App\Models\Company;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Traits\WithDeleteConfirmation;

#[Layout('components.layouts.app')]
#[Title('Positions')]
class Index extends Component
{
    use WithPagination, WithDeleteConfirmation;

    public $selectedCompanyId = null;
    public $selectedCompany = null;
    public $companies = [];
    public $isSuperadmin = false;

    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'sort')]
    public $sortBy = 'name_asc';

    #[Url(as: 'grade')]
    public $gradeFilter = '';

    #[Url(as: 'band')]
    public $bandFilter = '';

    #[Url(as: 'department')]
    public $departmentFilter = '';

    #[Url(as: 'location')]
    public $locationFilter = '';

    public function mount(): void
    {
        $user = Auth::user();

        $this->isSuperadmin = $user->hasRole('superadmin');

        if ($this->isSuperadmin) {
            $this->companies = Company::where('is_active', true)->get();
            $this->selectedCompanyId = session('positions_company_id') ?? request('company_id');

            if ($this->selectedCompanyId) {
                $this->selectedCompany = Company::find($this->selectedCompanyId);
            }
        } else {
            $this->selectedCompanyId = $user->company_id;
            $this->selectedCompany = Company::find($this->selectedCompanyId);
            $this->companies = Company::where('id', $user->company_id)->get();
        }
    }

    public function selectCompany($companyId)
    {
        $this->selectedCompanyId = $companyId;
        $this->selectedCompany = Company::find($companyId);
        session(['positions_company_id' => $companyId]);
        $this->resetPage();
        $this->resetFilters();
    }

    public function changeCompany()
    {
        $this->selectedCompanyId = null;
        $this->selectedCompany = null;
        session()->forget('positions_company_id');
        $this->resetPage();
        $this->resetFilters();
    }

    private function resetFilters()
    {
        $this->search = '';
        $this->sortBy = 'name_asc';
        $this->gradeFilter = '';
        $this->bandFilter = '';
        $this->departmentFilter = '';
        $this->locationFilter = '';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function updatingGradeFilter()
    {
        $this->resetPage();
    }

    public function updatingBandFilter()
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
        // Jika superadmin belum pilih company
        if ($this->isSuperadmin && !$this->selectedCompanyId) {
            return view('livewire.pages.positions.index', [
                'showCompanySelection' => true,
                'companies' => $this->companies,
                'isSuperadmin' => $this->isSuperadmin,
                'selectedCompany' => null,
                'positions' => collect(),
                'bands' => [],
                'grades' => [],
                'departments' => [],
                'locations' => [],
            ]);
        }

        $positions = Position::query()
            ->with(['company', 'employee'])
            ->where('company_id', $this->selectedCompanyId)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    // Pencarian hanya pada nama posisi dan kode posisi
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('position_code', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->gradeFilter, fn($query) => $query->where('job_grade', $this->gradeFilter))
            ->when($this->bandFilter, fn($query) => $query->where('band', $this->bandFilter))
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

        // Get unique values for filters from selected company only
        $bands = Position::where('company_id', $this->selectedCompanyId)
            ->distinct()
            ->whereNotNull('band')
            ->orderBy('band')
            ->pluck('band')
            ->toArray();

        $grades = Position::where('company_id', $this->selectedCompanyId)
            ->distinct()
            ->whereNotNull('job_grade')
            ->orderBy('job_grade')
            ->pluck('job_grade')
            ->toArray();

        $departments = Position::where('company_id', $this->selectedCompanyId)
            ->distinct()
            ->whereNotNull('department')
            ->orderBy('department')
            ->pluck('department')
            ->toArray();

        $locations = Position::where('company_id', $this->selectedCompanyId)
            ->distinct()
            ->whereNotNull('location')
            ->orderBy('location')
            ->pluck('location')
            ->toArray();

        return view('livewire.pages.positions.index', [
            'showCompanySelection' => false,
            'positions' => $positions,
            'bands' => $bands,
            'grades' => $grades,
            'departments' => $departments,
            'locations' => $locations,
            'selectedCompany' => $this->selectedCompany,
            'isSuperadmin' => $this->isSuperadmin,
            'companies' => $this->companies,
        ]);
    }
}
