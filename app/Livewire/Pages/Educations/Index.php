<?php

namespace App\Livewire\Pages\Educations;

use App\Models\Education;
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
#[Title('Education')]
class Index extends Component
{
    use WithPagination, WithDeleteConfirmation;

    public $selectedCompanyId = null;
    public $selectedCompany = null;
    public $companies = [];
    public $isSuperadmin = false;

    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'institution')]
    public $institutionFilter = '';

    #[Url(as: 'degree')]
    public $degreeFilter = '';

    #[Url(as: 'major')]
    public $majorFilter = '';

    public function mount(): void
    {
        $user = Auth::user();

        $this->isSuperadmin = $user->hasRole('superadmin');

        if ($this->isSuperadmin) {
            $this->companies = Company::where('is_active', true)->get();
            $this->selectedCompanyId = session('educations_company_id') ?? request('company_id');

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
        session(['educations_company_id' => $companyId]);
        $this->resetPage();
        $this->resetFilters();
    }

    public function changeCompany()
    {
        $this->selectedCompanyId = null;
        $this->selectedCompany = null;
        session()->forget('educations_company_id');
        $this->resetPage();
        $this->resetFilters();
    }

    private function resetFilters()
    {
        $this->search = '';
        $this->institutionFilter = '';
        $this->degreeFilter = '';
        $this->majorFilter = '';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingInstitutionFilter()
    {
        $this->resetPage();
    }

    public function updatingDegreeFilter()
    {
        $this->resetPage();
    }

    public function updatingMajorFilter()
    {
        $this->resetPage();
    }

    public function edit($educationId)
    {
        $education = Education::findOrFail($educationId);
        return $this->redirect(route('educations.edit', $education->id), navigate: true);
    }

    protected function getModelClass(): string
    {
        return Education::class;
    }

    protected function getModelDisplayName(): string
    {
        return 'Education';
    }

    protected function performDelete($education): void
    {
        $education->delete();
    }

    public function render()
    {
        // Jika superadmin belum pilih company
        if ($this->isSuperadmin && !$this->selectedCompanyId) {
            return view('livewire.pages.educations.index', [
                'showCompanySelection' => true,
                'companies' => $this->companies,
                'isSuperadmin' => $this->isSuperadmin,
                'selectedCompany' => null,
                'education' => collect(),
            ]);
        }

        $education = Education::query()
            ->with(['employee.company'])
            ->whereHas('employee', function ($query) {
                $query->where('company_id', $this->selectedCompanyId);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('employee', function ($employeeQuery) {
                        $employeeQuery->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('employee_number', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->when($this->institutionFilter, fn($query) => $query->where('institution_name', 'like', '%' . $this->institutionFilter . '%'))
            ->when($this->degreeFilter, fn($query) => $query->where('degree', $this->degreeFilter))
            ->when($this->majorFilter, fn($query) => $query->where('major', 'like', '%' . $this->majorFilter . '%'))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get unique values for filters from selected company only
        $institutions = Education::whereHas('employee', function ($query) {
            $query->where('company_id', $this->selectedCompanyId);
        })
            ->select('institution_name')
            ->distinct()
            ->whereNotNull('institution_name')
            ->orderBy('institution_name')
            ->pluck('institution_name')
            ->toArray();

        $degrees = Education::whereHas('employee', function ($query) {
            $query->where('company_id', $this->selectedCompanyId);
        })
            ->select('degree')
            ->distinct()
            ->whereNotNull('degree')
            ->orderBy('degree')
            ->pluck('degree')
            ->toArray();

        $majors = Education::whereHas('employee', function ($query) {
            $query->where('company_id', $this->selectedCompanyId);
        })
            ->select('major')
            ->distinct()
            ->whereNotNull('major')
            ->orderBy('major')
            ->pluck('major')
            ->toArray();

        return view('livewire.pages.educations.index', [
            'showCompanySelection' => false,
            'education' => $education,
            'selectedCompany' => $this->selectedCompany,
            'isSuperadmin' => $this->isSuperadmin,
            'companies' => $this->companies,
            'institutions' => $institutions,
            'degrees' => $degrees,
            'majors' => $majors,
        ]);
    }
}
