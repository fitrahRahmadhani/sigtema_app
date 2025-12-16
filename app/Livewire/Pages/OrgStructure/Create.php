<?php

namespace App\Livewire\Pages\OrgStructure;

use App\Models\Company;
use Livewire\Component;
use App\Models\Position;
use Livewire\Attributes\Title;
use Masmerise\Toaster\Toaster;
use Livewire\Attributes\Layout;
use App\Models\EmployeeHierarchy;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
#[Title('Create Employee Hierarchy')]
class Create extends Component
{
    #[Validate('required|exists:companies,id')]
    public $company_id;

    #[Validate('required|exists:positions,id')]
    public $position_id;

    #[Validate('required|exists:positions,id')]
    public $superior_id;

    #[Validate('required|string|max:255')]
    public $relation_type = 'direct';

    public $positions = [];
    public $superiors = [];

    public $company;

    public function mount()
    {
        if (!Auth::user()->hasRole('superadmin')) {
            $this->company_id = Auth::user()->company_id;
            $this->company = Company::find($this->company_id);
        } else {
            $this->company_id = session('selected_company_id');
        }
    }

    public function updatedCompanyId()
    {
        $this->positions = Position::where('company_id', $this->company_id)
            ->get();

        $this->superiors = Position::where('company_id', $this->company_id)
            ->get();

        $this->reset(['position_id', 'superior_id']);
    }

    public function updatedPositionId()
    {
        // Filter out the selected position from superiors to prevent self-reference
        $this->superiors = Position::where('company_id', $this->company_id)
            ->where('id', '!=', $this->position_id)
            ->get();
    }

    public function save()
    {
        $this->validate([
            'company_id' => 'required|exists:companies,id',
            'position_id' => 'required|exists:positions,id|different:superior_id',
            'superior_id' => 'required|exists:positions,id|different:position_id',
            'relation_type' => 'required|string|max:255',
        ]);

        // Check if hierarchy already exists
        $exists = EmployeeHierarchy::where('company_id', $this->company_id)
            ->where('position_id', $this->position_id)
            ->where('superior_id', $this->superior_id)
            ->exists();

        if ($exists) {
            Toaster::error('This hierarchy relationship already exists.');
            return;
        }

        EmployeeHierarchy::create([
            'company_id' => $this->company_id,
            'position_id' => $this->position_id,
            'superior_id' => $this->superior_id,
            'relation_type' => $this->relation_type,
        ]);

        return redirect()->route('organization-structure.table')
            ->success('Hierarchy created successfully.');
    }

    public function render()
    {
        $companies = Company::where('is_active', true)->get();
        return view('livewire.pages.org-structure.create', [
            'companies' => $companies,
        ]);
    }
}
