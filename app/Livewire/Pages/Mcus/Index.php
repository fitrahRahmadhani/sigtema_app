<?php

namespace App\Livewire\Pages\Mcus;

use App\Livewire\Traits\HandleException;
use App\Livewire\Traits\WithDeleteConfirmation;
use App\Models\Company;
use App\Models\Mcu;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Medical Check Up')]
class Index extends Component
{
    use WithPagination, WithDeleteConfirmation, HandleException;

    public User $user;
    public $selectedCompanyId = null;
    public $selectedCompany = null;
    public $companies = [];
    public $isSuperadmin = false;

    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'year')]
    public $yearFilter = '';

    #[Url(as: 'result')]
    public $resultFilter = '';

    public function mount(): void
    {
        $this->user = Auth::user();

        $this->isSuperadmin = $this->user->hasRole('superadmin');

        if ($this->isSuperadmin) {
            $this->companies = Company::where('is_active', true)->get();
            $this->selectedCompanyId = session('mcus_company_id') ?? request('company_id');

            if ($this->selectedCompanyId) {
                $this->selectedCompany = Company::find($this->selectedCompanyId);
            }
        } else {
            $this->selectedCompanyId = $this->user->company_id;
            $this->selectedCompany = Company::find($this->selectedCompanyId);
            $this->companies = Company::where('id', $this->user->company_id)->get();
        }
    }

    public function selectCompany($companyId)
    {
        $this->selectedCompanyId = $companyId;
        $this->selectedCompany = Company::find($companyId);
        session(['mcus_company_id' => $companyId]);
        $this->resetPage();
        $this->resetFilters();
    }

    public function changeCompany()
    {
        $this->selectedCompanyId = null;
        $this->selectedCompany = null;
        session()->forget('mcus_company_id');
        $this->resetPage();
        $this->resetFilters();
    }

    private function resetFilters()
    {
        $this->search = '';
        $this->yearFilter = '';
        $this->resultFilter = '';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingYearFilter()
    {
        $this->resetPage();
    }

    public function updatingResultFilter()
    {
        $this->resetPage();
    }

    public function edit($mcuId)
    {
        $mcu = Mcu::findOrFail($mcuId);
        return $this->redirect(route('mcus.edit', $mcu->id), navigate: true);
    }

    protected function getModelClass(): string
    {
        return Mcu::class;
    }

    protected function getModelDisplayName(): string
    {
        return 'Medical Check Up Record';
    }

    protected function performDelete($mcu): void
    {

        $this->authorize('delete', [Mcu::class, $mcu]);

        try {
            DB::beginTransaction();
            $mcu->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->handleException('delete mcu', $e->getMessage());
        }
    }

    public function render()
    {
        // Jika superadmin belum pilih company
        if ($this->isSuperadmin && !$this->selectedCompanyId) {
            return view('livewire.pages.mcus.index', [
                'showCompanySelection' => true,
                'companies' => $this->companies,
                'isSuperadmin' => $this->isSuperadmin,
                'selectedCompany' => null,
                'mcus' => collect(),
            ]);
        }

        $mcus = Mcu::query()
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
            ->when($this->yearFilter, fn($query) => $query->where('year', $this->yearFilter))
            ->when($this->resultFilter, fn($query) => $query->where('result', $this->resultFilter))
            ->orderBy('year', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get unique values for filters from selected company only
        $years = Mcu::whereHas('employee', function ($query) {
            $query->where('company_id', $this->selectedCompanyId);
        })
            ->select('year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        $resultOptions = ['1', '2', '3', '4', '5'];

        return view('livewire.pages.mcus.index', [
            'showCompanySelection' => false,
            'mcus' => $mcus,
            'selectedCompany' => $this->selectedCompany,
            'isSuperadmin' => $this->isSuperadmin,
            'companies' => $this->companies,
            'years' => $years,
            'resultOptions' => $resultOptions,
        ]);
    }
}
