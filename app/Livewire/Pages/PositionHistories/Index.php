<?php

namespace App\Livewire\Pages\PositionHistories;

use App\Livewire\Traits\HandleException;
use App\Livewire\Traits\WithDeleteConfirmation;
use App\Models\Company;
use App\Models\Position;
use App\Models\PositionHistory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Position Histories')]
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

    public function mount(): void
    {
        $this->user = Auth::user();

        $this->isSuperadmin = $this->user->hasRole('superadmin');

        if ($this->isSuperadmin) {
            $this->companies = Company::where('is_active', true)->get();
            $this->selectedCompanyId = session('position_histories_company_id') ?? request('company_id');

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
        session(['position_histories_company_id' => $companyId]);
        $this->resetPage();
        $this->resetFilters();
    }

    public function changeCompany()
    {
        $this->selectedCompanyId = null;
        $this->selectedCompany = null;
        session()->forget('position_histories_company_id');
        $this->resetPage();
        $this->resetFilters();
    }

    private function resetFilters()
    {
        $this->search = '';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function edit($historyId)
    {
        $history = PositionHistory::findOrFail($historyId);
        return $this->redirect(route('position-histories.edit', $history->id), navigate: true);
    }

    protected function getModelClass(): string
    {
        return PositionHistory::class;
    }

    protected function getModelDisplayName(): string
    {
        return 'Position History';
    }

    protected function performDelete($history): void
    {

        $this->authorize('delete', [PositionHistory::class, $history]);

        try {
            DB::beginTransaction();
            $history->delete();
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
            return view('livewire.pages.position-histories.index', [
                'showCompanySelection' => true,
                'companies' => $this->companies,
                'isSuperadmin' => $this->isSuperadmin,
                'selectedCompany' => null,
                'histories' => collect(),
            ]);
        }

        // Query position histories berdasarkan company yang dipilih
        $histories = PositionHistory::query()
            ->with(['employee'])
            ->whereHas('employee', function ($query) {
                $query->where('company_id', $this->selectedCompanyId);
            })
            ->when($this->search, function ($query) {
                $query->whereHas('employee', function ($employeeQuery) {
                    $employeeQuery->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('employee_number', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('start_date', 'desc')
            ->paginate(10);

        return view('livewire.pages.position-histories.index', [
            'showCompanySelection' => false,
            'histories' => $histories,
            'selectedCompany' => $this->selectedCompany,
            'isSuperadmin' => $this->isSuperadmin,
            'companies' => $this->companies,
        ]);
    }
}
