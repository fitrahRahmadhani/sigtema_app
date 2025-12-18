<?php

namespace App\Livewire\Pages\PsychogramAspects;

use App\Models\PsychogramAspect;
use App\Models\Company;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Traits\WithDeleteConfirmation;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Psychogram Aspects')]
class Index extends Component
{
    use WithPagination, WithDeleteConfirmation;

    public $selectedCompanyId = null;
    public $selectedCompany = null;
    public $companies = [];
    public $isSuperadmin = false;
    public $showCompanySelection = false;

    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'sort')]
    public $sortBy = 'sort_order_asc';

    #[Url(as: 'status')]
    public $statusFilter = '';

    public function mount(): void
    {
        $user = Auth::user();

        $this->isSuperadmin = $user->hasRole('superadmin');

        if ($this->isSuperadmin) {
            $this->companies = Company::where('is_active', true)->get();
            $this->selectedCompanyId = session('psychogram_aspects_company_id') ?? request('company_id');

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
        session(['psychogram_aspects_company_id' => $companyId]);
        $this->showCompanySelection = false;
        $this->resetPage();
        $this->resetFilters();
    }

    public function changeCompany()
    {
        $this->selectedCompanyId = null;
        $this->selectedCompany = null;
        session()->forget('psychogram_aspects_company_id');
        $this->showCompanySelection = true;
        $this->resetPage();
        $this->resetFilters();
    }

    private function resetFilters()
    {
        $this->search = '';
        $this->sortBy = 'sort_order_asc';
        $this->statusFilter = '';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function edit($aspectId)
    {
        $aspect = PsychogramAspect::findOrFail($aspectId);
        return $this->redirect(route('psychogram-aspects.edit', $aspect->id), navigate: true);
    }

    public function toggleStatus($aspectId)
    {
        $aspect = PsychogramAspect::findOrFail($aspectId);

        // Authorization check
        if (!$this->isSuperadmin) {
            // Admin biasa hanya bisa toggle status untuk:
            // 1. Global aspects (company_id null) - TIDAK BISA
            // 2. Aspects dari company mereka sendiri
            if ($aspect->company_id === null) {
                Toaster::error('You are not authorized to toggle status of global aspects.');
                return;
            }

            if ($aspect->company_id !== $this->selectedCompanyId) {
                Toaster::error('You are not authorized to toggle status of aspects from other companies.');
                return;
            }
        }

        // Superadmin bisa toggle semua aspects (global dan company-specific)

        $aspect->update(['is_active' => !$aspect->is_active]);

        Toaster::success('Status updated successfully!');
    }

    protected function getModelClass(): string
    {
        return PsychogramAspect::class;
    }

    protected function getModelDisplayName(): string
    {
        return 'Psychogram Aspect';
    }

    protected function performDelete($aspect): void
    {
        // Authorization check untuk delete
        if (!$this->isSuperadmin) {
            // Admin biasa hanya bisa delete:
            // 1. Global aspects (company_id null) - TIDAK BISA
            // 2. Aspects dari company mereka sendiri
            if ($aspect->company_id === null) {
                Toaster::error('You are not authorized to delete global aspects.');
                return;
            }

            if ($aspect->company_id !== $this->selectedCompanyId) {
                Toaster::error('You are not authorized to delete aspects from other companies.');
                return;
            }
        }

        // Cek jika aspect digunakan di psychogram scores sebelum menghapus
        if ($aspect->scores()->exists()) {
            Toaster::error('Cannot delete aspect because it is being used in psychogram scores.');
            return;
        }

        $aspect->delete();
    }

    public function render()
    {
        // Jika superadmin belum pilih company
        if ($this->showCompanySelection) {
            // Hitung jumlah data aspect per perusahaan (termasuk global)
            $companiesWithCount = $this->companies->map(function ($company) {
                $count = PsychogramAspect::where(function ($query) use ($company) {
                    $query->where('company_id', $company->id)
                        ->orWhereNull('company_id');
                })->count();

                return [
                    'company' => $company,
                    'count' => $count
                ];
            });

            return view('livewire.pages.psychogram-aspects.index', [
                'showCompanySelection' => true,
                'companiesWithCount' => $companiesWithCount,
                'isSuperadmin' => $this->isSuperadmin,
                'selectedCompany' => null,
                'aspects' => collect(),
            ]);
        }

        // Query aspects berdasarkan company yang dipilih
        // Note: PsychogramAspect bisa memiliki company_id null (global aspects)
        $aspects = PsychogramAspect::query()
            ->with(['company'])
            ->where(function ($query) {
                // Tampilkan global aspects (company_id null) DAN aspects dari company yang dipilih
                $query->whereNull('company_id')
                    ->orWhere('company_id', $this->selectedCompanyId);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    // Pencarian hanya untuk aspect_code dan aspect_name
                    $q->where('aspect_code', 'like', '%' . $this->search . '%')
                        ->orWhere('aspect_name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter === 'active', fn($query) => $query->where('is_active', true))
            ->when($this->statusFilter === 'inactive', fn($query) => $query->where('is_active', false))
            ->when($this->sortBy === 'aspect_code_asc', fn($query) => $query->orderBy('aspect_code', 'asc'))
            ->when($this->sortBy === 'aspect_code_desc', fn($query) => $query->orderBy('aspect_code', 'desc'))
            ->when($this->sortBy === 'aspect_name_asc', fn($query) => $query->orderBy('aspect_name', 'asc'))
            ->when($this->sortBy === 'aspect_name_desc', fn($query) => $query->orderBy('aspect_name', 'desc'))
            ->when($this->sortBy === 'sort_order_asc', fn($query) => $query->orderBy('sort_order', 'asc'))
            ->when($this->sortBy === 'sort_order_desc', fn($query) => $query->orderBy('sort_order', 'desc'))
            ->when($this->sortBy === 'newest', fn($query) => $query->latest('created_at'))
            ->when($this->sortBy === 'oldest', fn($query) => $query->oldest('created_at'))
            ->paginate(10);

        return view('livewire.pages.psychogram-aspects.index', [
            'showCompanySelection' => false,
            'aspects' => $aspects,
            'selectedCompany' => $this->selectedCompany,
            'isSuperadmin' => $this->isSuperadmin,
            'companies' => $this->companies,
        ]);
    }
}
