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
use Illuminate\Support\Facades\Auth;
use App\Livewire\Traits\WithDeleteConfirmation;

#[Layout('components.layouts.app')]
#[Title('Technical Competencies')]
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
    public $sortBy = 'year_desc';

    #[Url(as: 'year')]
    public $yearFilter = '';

    #[Url(as: 'issued_by')]
    public $issuedByFilter = '';

    public function mount(): void
    {
        $user = Auth::user();

        $this->isSuperadmin = $user->hasRole('superadmin');

        if ($this->isSuperadmin) {
            $this->companies = Company::where('is_active', true)->get();
            $this->selectedCompanyId = session('technical_competencies_company_id') ?? request('company_id');

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
        session(['technical_competencies_company_id' => $companyId]);
        $this->resetPage();
        $this->resetFilters();
    }

    public function changeCompany()
    {
        $this->selectedCompanyId = null;
        $this->selectedCompany = null;
        session()->forget('technical_competencies_company_id');
        $this->resetPage();
        $this->resetFilters();
    }

    private function resetFilters()
    {
        $this->search = '';
        $this->sortBy = 'year_desc';
        $this->yearFilter = '';
        $this->issuedByFilter = '';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
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
        // Jika superadmin belum pilih company
        if ($this->isSuperadmin && !$this->selectedCompanyId) {
            return view('livewire.pages.technical-competencies.index', [
                'showCompanySelection' => true,
                'companies' => $this->companies,
                'isSuperadmin' => $this->isSuperadmin,
                'selectedCompany' => null,
                'competencies' => collect(),
                'years' => collect(),
                'issuedBys' => collect(),
            ]);
        }

        // Query competencies berdasarkan company yang dipilih
        $competencies = TechnicalCompetency::query()
            ->with(['employee.company'])
            ->whereHas('employee', function ($query) {
                $query->where('company_id', $this->selectedCompanyId);
            })
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

        // Get unique years for filter from selected company only
        $years = TechnicalCompetency::whereHas('employee', function ($query) {
            $query->where('company_id', $this->selectedCompanyId);
        })
            ->select('year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Get unique issued_by for filter from selected company only
        $issuedBys = TechnicalCompetency::whereHas('employee', function ($query) {
            $query->where('company_id', $this->selectedCompanyId);
        })
            ->select('issued_by')
            ->distinct()
            ->orderBy('issued_by')
            ->pluck('issued_by');

        return view('livewire.pages.technical-competencies.index', [
            'showCompanySelection' => false,
            'competencies' => $competencies,
            'selectedCompany' => $this->selectedCompany,
            'isSuperadmin' => $this->isSuperadmin,
            'companies' => $this->companies,
            'years' => $years,
            'issuedBys' => $issuedBys,
        ]);
    }
}
