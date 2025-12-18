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
use Illuminate\Support\Facades\Auth;
use App\Livewire\Traits\WithDeleteConfirmation;

#[Layout('components.layouts.app')]
#[Title('Psychogram Results')]
class Index extends Component
{
    use WithPagination, WithDeleteConfirmation;

    public $selectedCompanyId = null;
    public $selectedCompany = null;
    public $companies;
    public $isSuperadmin = false;
    public $showCompanySelection = false;

    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'aspect')]
    public $aspectFilter = '';

    #[Url(as: 'score_min')]
    public $scoreMin = '';

    #[Url(as: 'score_max')]
    public $scoreMax = '';

    #[Url(as: 'sort')]
    public $sortBy = 'created_desc';

    public function mount(): void
    {
        $user = Auth::user();

        $this->isSuperadmin = $user->hasRole('superadmin');

        if ($this->isSuperadmin) {
            $this->companies = Company::where('is_active', true)->get();
            $this->selectedCompanyId = session('psychogram_results_company_id') ?? request('company_id');

            if ($this->selectedCompanyId) {
                $this->selectedCompany = Company::find($this->selectedCompanyId);
                $this->showCompanySelection = false;
            } else {
                $this->showCompanySelection = true;
            }
        } else {
            $this->selectedCompanyId = $user->company_id;
            $this->selectedCompany = Company::find($this->selectedCompanyId);
            $this->companies = Company::where('id', $user->company_id)->get();
            $this->showCompanySelection = false;
        }
    }

    public function selectCompany($companyId)
    {
        $this->selectedCompanyId = $companyId;
        $this->selectedCompany = Company::find($companyId);
        session(['psychogram_results_company_id' => $companyId]);
        $this->showCompanySelection = false;
        $this->resetPage();
        $this->resetFilters();
    }

    public function changeCompany()
    {
        $this->selectedCompanyId = null;
        $this->selectedCompany = null;
        session()->forget('psychogram_results_company_id');
        $this->showCompanySelection = true;
        $this->resetPage();
        $this->resetFilters();
    }

    private function resetFilters()
    {
        $this->search = '';
        $this->aspectFilter = '';
        $this->scoreMin = '';
        $this->scoreMax = '';
        $this->sortBy = 'created_desc';
    }

    public function updatingSearch()
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
        // Jika superadmin belum pilih company
        if ($this->showCompanySelection) {
            // Hitung jumlah data psychogram per perusahaan
            $companiesWithCount = $this->companies->map(function ($company) {
                $count = PsychogramResult::whereHas('employee', function ($query) use ($company) {
                    $query->where('company_id', $company->id);
                })->count();

                return [
                    'company' => $company,
                    'count' => $count
                ];
            });

            return view('livewire.pages.psychogram-results.index', [
                'showCompanySelection' => true,
                'companiesWithCount' => $companiesWithCount,
                'isSuperadmin' => $this->isSuperadmin,
                'selectedCompany' => null,
                'results' => collect(),
            ]);
        }

        $results = PsychogramResult::query()
            ->with(['employee.company', 'aspect'])
            ->whereHas('employee', function ($query) {
                $query->where('company_id', $this->selectedCompanyId);
            })
            ->when($this->search, function ($query) {
                $query->whereHas('employee', function ($employeeQuery) {
                    $employeeQuery->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('employee_number', 'like', '%' . $this->search . '%');
                });
            })
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

        // Get aspects for filter menggunakan method yang sudah ada di model
        $aspects = PsychogramAspect::getAvailableAspects($this->selectedCompanyId);

        return view('livewire.pages.psychogram-results.index', [
            'showCompanySelection' => false,
            'results' => $results,
            'selectedCompany' => $this->selectedCompany,
            'isSuperadmin' => $this->isSuperadmin,
            'companies' => $this->companies,
            'aspects' => $aspects,
        ]);
    }
}
