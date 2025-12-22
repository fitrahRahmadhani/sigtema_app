<?php

namespace App\Livewire\Pages\Requirements\Positions;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use App\Models\Position;
use App\Models\PositionRequirementSetting;
use Illuminate\Support\Facades\Auth;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Create Position Requirements')]
class Create extends Component
{
    public $position_id = null;
    public $position = null;
    public $companies = [];
    public $positions = [];
    public $isSuperadmin = false;
    public $company_id = null;

    // Dynamic requirements
    public $requirements = [
        ['name' => '', 'value' => '']
    ];

    public function mount($position_id = null)
    {
        $user = Auth::user();
        $this->isSuperadmin = $user->hasRole('superadmin');

        // Load companies for superadmin
        if ($this->isSuperadmin) {
            $this->companies = \App\Models\Company::where('is_active', true)->get();
        } else {
            $this->company_id = $user->company_id;
            $this->loadPositions();
        }

        // If position_id is provided, load that position
        if ($position_id) {
            $this->position_id = $position_id;
            $this->position = Position::with('company')->find($position_id);

            if ($this->position) {
                $this->company_id = $this->position->company_id;
                if ($this->isSuperadmin) {
                    $this->loadPositions();
                }
            }
        }
    }

    public function loadPositions()
    {
        if ($this->company_id) {
            $this->positions = Position::where('company_id', $this->company_id)
                ->orderBy('position_code')
                ->get();
        }
    }

    public function updatedCompanyId()
    {
        $this->position_id = null;
        $this->position = null;
        $this->loadPositions();
    }

    public function updatedPositionId()
    {
        if ($this->position_id) {
            $this->position = Position::with('company')->find($this->position_id);
        } else {
            $this->position = null;
        }
    }

    // Add new requirement row
    public function addRequirementRow()
    {
        $this->requirements[] = ['name' => '', 'value' => ''];
    }

    // Remove requirement row
    public function removeRequirementRow($index)
    {
        if (count($this->requirements) > 1) {
            unset($this->requirements[$index]);
            $this->requirements = array_values($this->requirements);
        }
    }

    // Save requirements
    public function save()
    {
        // Validate position selection
        if (!$this->position_id) {
            Toaster::warning('Please select a position.');
            return;
        }

        // Validate requirements
        $validRequirements = [];
        foreach ($this->requirements as $index => $requirement) {
            $name = trim($requirement['name'] ?? '');
            $value = trim($requirement['value'] ?? '');

            // Skip empty rows
            if (empty($name) && empty($value)) {
                continue;
            }

            // Validate that name is not empty
            if (empty($name)) {
                Toaster::warning("Requirement name cannot be empty in row " . ($index + 1));
                return;
            }

            $validRequirements[] = [
                'name' => $name,
                'value' => $value
            ];
        }

        // Check if there's at least one valid requirement
        if (empty($validRequirements)) {
            Toaster::warning('Please add at least one requirement.');
            return;
        }

        // Check if requirements already exist for this position
        $existingRequirements = PositionRequirementSetting::where('position_id', $this->position_id)->exists();
        if ($existingRequirements) {
            Toaster::warning('Requirements already exist for this position. Please use the edit page instead.');
            return redirect()->route('requirements.position.edit', ['position' => $this->position_id]);
        }

        try {
            // Save all requirements
            foreach ($validRequirements as $requirement) {
                PositionRequirementSetting::create([
                    'position_id' => $this->position_id,
                    'position_code' => $this->position->position_code,
                    'name' => $requirement['name'],
                    'value' => $requirement['value']
                ]);
            }

            Toaster::success(count($validRequirements) . ' requirements created successfully!');
            return redirect()->route('requirements.position');
        } catch (\Exception $e) {
            Toaster::error('Failed to create requirements: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('requirements.position');
    }

    public function render()
    {
        return view('livewire.pages.requirements.positions.create');
    }
}
