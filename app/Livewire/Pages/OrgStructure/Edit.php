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

#[Layout('components.layouts.app')]
#[Title('Edit Employee Hierarchy')]
class Edit extends Component
{
    public EmployeeHierarchy $hierarchy;

    #[Validate('required|exists:companies,id')]
    public $company_id;

    #[Validate('required|exists:positions,id')]
    public $position_id;

    #[Validate('required|exists:positions,id')]
    public $superior_id;

    #[Validate('required|string|max:255')]
    public $relation_type;

    public $positions = [];
    public $superiors = [];

    public function mount(EmployeeHierarchy $hierarchy)
    {
        $this->hierarchy = $hierarchy;
        $this->company_id = $hierarchy->company_id;
        $this->position_id = $hierarchy->position_id;
        $this->superior_id = $hierarchy->superior_id;
        $this->relation_type = $hierarchy->relation_type;

        // Load positions for the selected company
        $this->positions = Position::where('company_id', $this->company_id)
            ->get();

        $this->superiors = Position::where('company_id', $this->company_id)
            ->where('id', '!=', $this->position_id)
            ->get();
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

        // Check if hierarchy already exists (except current)
        $exists = EmployeeHierarchy::where('company_id', $this->company_id)
            ->where('position_id', $this->position_id)
            ->where('superior_id', $this->superior_id)
            ->where('id', '!=', $this->hierarchy->id)
            ->exists();

        if ($exists) {
            Toaster::error('This hierarchy relationship already exists.');
            return;
        }

        $this->hierarchy->update([
            'company_id' => $this->company_id,
            'position_id' => $this->position_id,
            'superior_id' => $this->superior_id,
            'relation_type' => $this->relation_type,
        ]);

        return redirect()->route('organization-structure.table')
            ->success('Hierarchy updated successfully.');
    }
    public function render()
    {
        $companies = Company::where('is_active', true)->get();

        return view('livewire.pages.org-structure.edit', [
            'companies' => $companies,
        ]);
    }
}
