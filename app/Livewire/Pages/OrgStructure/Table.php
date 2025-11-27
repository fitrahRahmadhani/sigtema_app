<?php

namespace App\Livewire\Pages\OrgStructure;

use App\Models\EmployeeHierarchy;
use App\Models\Company;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Traits\WithDeleteConfirmation;

#[Layout('components.layouts.app')]
#[Title('Organization Structure')]
class Table extends Component
{
    use WithPagination, WithDeleteConfirmation;

    public $search = '';
    public $companyFilter = '';
    public $relationTypeFilter = '';
    public $perPage = 10;

    public $isSuperadmin = false;
    public $selectedCompanyId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'companyFilter' => ['except' => ''],
        'relationTypeFilter' => ['except' => ''],
    ];

    public function mount()
    {
        $this->initializeUserAccess();
    }

    private function initializeUserAccess()
    {
        $user = Auth::user();

        // Cek jika superadmin (company_id = null)
        $this->isSuperadmin = is_null($user->company_id);

        if ($this->isSuperadmin) {
            // Superadmin: ambil company yang dipilih dari session
            $this->selectedCompanyId = session('selected_company_id');

            // Jika ada company yang dipilih di session, set sebagai filter
            if ($this->selectedCompanyId) {
                $this->companyFilter = $this->selectedCompanyId;
            }
        } else {
            // Admin: pakai company mereka
            $this->selectedCompanyId = $user->company_id;
            $this->companyFilter = $this->selectedCompanyId;
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCompanyFilter()
    {
        $this->resetPage();
    }

    public function updatingRelationTypeFilter()
    {
        $this->resetPage();
    }

    // Implement abstract methods from trait
    protected function getModelClass(): string
    {
        return EmployeeHierarchy::class;
    }

    protected function getModelDisplayName(): string
    {
        return 'Hierarchy';
    }

    public function resetCompanyFilter()
    {
        $this->companyFilter = $this->selectedCompanyId;
        $this->resetPage();
    }

    public function render()
    {
        $hierarchies = EmployeeHierarchy::query()
            ->with(['company', 'position', 'superior'])
            ->when($this->search, function ($query) {
                $query->whereHas('position', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })->orWhereHas('superior', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })->orWhere('relation_type', 'like', '%' . $this->search . '%');
            })
            ->when($this->companyFilter, function ($query) {
                $query->where('company_id', $this->companyFilter);
            })
            ->when($this->relationTypeFilter, function ($query) {
                $query->where('relation_type', $this->relationTypeFilter);
            })
            // Tambahkan filter berdasarkan session company untuk superadmin
            ->when($this->isSuperadmin && $this->selectedCompanyId && !$this->companyFilter, function ($query) {
                $query->where('company_id', $this->selectedCompanyId);
            })
            ->latest()
            ->paginate($this->perPage);

        $companies = Company::where('is_active', true)->get();
        $relationTypes = EmployeeHierarchy::select('relation_type')
            ->distinct()
            ->pluck('relation_type');

        return view('livewire.pages.org-structure.table', [
            'hierarchies' => $hierarchies,
            'companies' => $companies,
            'relationTypes' => $relationTypes,
            'isSuperadmin' => $this->isSuperadmin,
            'selectedCompanyId' => $this->selectedCompanyId,
        ]);
    }
}
