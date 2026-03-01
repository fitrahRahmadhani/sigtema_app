<?php

namespace App\Livewire\Pages\OrgStructure;

use App\Livewire\Traits\HandleException;
use App\Livewire\Traits\WithDeleteConfirmation;
use App\Models\Company;
use App\Models\EmployeeHierarchy;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Organization Structure')]
class Table extends Component
{
    use WithPagination, WithDeleteConfirmation, HandleException;

    public User $user;
    public $search = '';
    public $companyFilter = '';
    public $relationTypeFilter = '';
    public $perPage = 10;

    public $isSuperadmin = false;
    public $selectedCompanyId = null;
    public $company;

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
        $this->user = Auth::user();

        // Cek jika superadmin (company_id = null)
        $this->isSuperadmin = $this->user->hasRole('superadmin');

        if ($this->isSuperadmin) {
            // Superadmin: ambil company yang dipilih dari session
            $this->selectedCompanyId = session('selected_company_id');

            // Jika ada company yang dipilih di session, set sebagai filter
            if ($this->selectedCompanyId) {
                $this->companyFilter = $this->selectedCompanyId;
            }
        } else {
            // Admin: pakai company mereka
            $this->selectedCompanyId = $this->user->company_id;
            $this->companyFilter = $this->selectedCompanyId;
            $this->company = $this->user->company->name;
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

    protected function performDelete($hierarchy): void
    {

        $this->authorize('delete', [EmployeeHierarchy::class, $hierarchy]);

        try {
            DB::beginTransaction();
            $hierarchy->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->handleException('delete hierarchy', $e->getMessage());
        }
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
