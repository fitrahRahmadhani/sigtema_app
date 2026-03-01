<?php

namespace App\Livewire\Pages\Requirements\Positions;

use App\Models\Company;
use App\Models\Position;
use App\Models\PositionRequirementSetting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Position Requirements')]
class Index extends Component
{
    use WithPagination;

    public User $user;
    public $search = '';
    public $companyId = null;
    public $isSuperadmin = false;
    public $showCompanySelection = false;
    public $selectedCompany = null;
    public $companies = [];

    public function mount()
    {
        $this->user = Auth::user();
        $this->isSuperadmin = $this->user->hasRole('superadmin');

        if ($this->isSuperadmin) {
            $this->companies = Company::where('is_active', true)->get();
            $this->companyId = session('requirements_company_id');

            if ($this->companyId) {
                $this->selectedCompany = Company::find($this->companyId);
            } else {
                $this->showCompanySelection = true;
            }
        } else {
            $this->companyId = $this->user->company_id;
            $this->selectedCompany = Company::find($this->companyId);
        }
    }

    public function selectCompany($companyId)
    {
        $this->companyId = $companyId;
        $this->selectedCompany = Company::find($companyId);
        session(['requirements_company_id' => $companyId]);
        $this->showCompanySelection = false;
        $this->resetPage();
    }

    public function changeCompany()
    {
        $this->companyId = null;
        $this->selectedCompany = null;
        $this->showCompanySelection = true;
        session()->forget('requirements_company_id');
        $this->resetPage();
    }

    public function render()
    {
        // Jika superadmin belum pilih company
        if ($this->isSuperadmin && $this->showCompanySelection) {
            return view('livewire.pages.requirements.positions.index', [
                'showCompanySelection' => true,
                'companies' => $this->companies,
                'isSuperadmin' => $this->isSuperadmin,
                'selectedCompany' => null,
                'positions' => collect(),
                'requirementsCounts' => [],
            ]);
        }

        // Query untuk positions yang ada di company yang dipilih
        $positions = Position::query()
            ->with(['company', 'employee'])
            ->where('company_id', $this->companyId)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('position_code', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('position_code')
            ->paginate(10);

        // Hitung jumlah requirements untuk setiap position
        $requirementsCounts = [];
        if ($positions->count() > 0) {
            $positionIds = $positions->pluck('id')->toArray();
            $requirements = PositionRequirementSetting::whereIn('position_id', $positionIds)
                ->selectRaw('position_id, COUNT(*) as count')
                ->groupBy('position_id')
                ->get()
                ->keyBy('position_id');

            foreach ($requirements as $positionId => $req) {
                $requirementsCounts[$positionId] = $req->count;
            }
        }

        return view('livewire.pages.requirements.positions.index', [
            'showCompanySelection' => false,
            'positions' => $positions,
            'requirementsCounts' => $requirementsCounts,
            'selectedCompany' => $this->selectedCompany,
            'isSuperadmin' => $this->isSuperadmin,
            'companies' => $this->companies,
        ]);
    }
}
