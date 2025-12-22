<?php

namespace App\Livewire\Pages\Requirements\Positions;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use App\Models\Position;
use App\Models\PositionRequirementSetting;
use App\Livewire\Traits\WithDeleteConfirmation;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Edit Position Requirements')]
class Edit extends Component
{
    use WithDeleteConfirmation;

    public $position;
    public $requirements = [];

    // Dynamic form untuk requirements baru
    public $newRequirements = [
        ['name' => '', 'value' => '']
    ];

    public function mount(Position $position)
    {
        $this->position = $position;
        $this->loadRequirements();
    }

    private function loadRequirements()
    {
        $this->requirements = PositionRequirementSetting::where('position_id', $this->position->id)
            ->orderBy('id')
            ->get()
            ->map(function ($req) {
                return [
                    'id' => $req->id,
                    'name' => $req->name,
                    'value' => $req->value
                ];
            })
            ->toArray();
    }

    // Update existing requirement
    public function updateRequirement($index)
    {
        if (!isset($this->requirements[$index]['id'])) {
            return;
        }

        $requirement = $this->requirements[$index];
        $name = trim($requirement['name'] ?? '');
        $value = trim($requirement['value'] ?? '');

        // Validate
        if (empty($name)) {
            Toaster::warning('Requirement name cannot be empty.');
            return;
        }

        try {
            PositionRequirementSetting::where('id', $requirement['id'])->update([
                'name' => $name,
                'value' => $value
            ]);

            Toaster::success('Requirement updated successfully!');

            // Refresh requirements setelah update
            $this->loadRequirements();
        } catch (\Exception $e) {
            Toaster::error('Failed to update requirement: ' . $e->getMessage());
        }
    }

    // Add new requirement row to dynamic form
    public function addNewRequirementRow()
    {
        $this->newRequirements[] = ['name' => '', 'value' => ''];
    }

    // Remove requirement row from dynamic form
    public function removeNewRequirementRow($index)
    {
        if (count($this->newRequirements) > 1) {
            unset($this->newRequirements[$index]);
            $this->newRequirements = array_values($this->newRequirements);
        }
    }

    // Add all new requirements
    public function addRequirements()
    {
        $validRequirements = [];

        // Validate all new requirements
        foreach ($this->newRequirements as $index => $requirement) {
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

        try {
            // Save all new requirements
            foreach ($validRequirements as $requirement) {
                PositionRequirementSetting::create([
                    'position_id' => $this->position->id,
                    'position_code' => $this->position->position_code,
                    'name' => $requirement['name'],
                    'value' => $requirement['value']
                ]);
            }

            // Reset new requirements form
            $this->newRequirements = [['name' => '', 'value' => '']];

            // Reload all requirements
            $this->loadRequirements();

            Toaster::success(count($validRequirements) . ' new requirements added successfully!');
        } catch (\Exception $e) {
            Toaster::error('Failed to add requirements: ' . $e->getMessage());
        }
    }

    // Get model class for trait
    protected function getModelClass(): string
    {
        return PositionRequirementSetting::class;
    }

    // Get model display name for modal
    protected function getModelDisplayName(): string
    {
        return 'Requirement';
    }

    // Custom deletion logic for requirements
    protected function performDelete($item): void
    {
        $item->delete();

        // Refresh requirements setelah delete
        $this->loadRequirements();
    }

    // Save all changes
    public function save()
    {
        // Validate all existing requirements
        foreach ($this->requirements as $index => $requirement) {
            $name = trim($requirement['name'] ?? '');

            if (empty($name)) {
                Toaster::warning("Requirement name cannot be empty in row " . ($index + 1));
                return;
            }
        }

        // Update all existing requirements
        $successCount = 0;
        foreach ($this->requirements as $requirement) {
            if (isset($requirement['id'])) {
                try {
                    PositionRequirementSetting::where('id', $requirement['id'])->update([
                        'name' => trim($requirement['name']),
                        'value' => trim($requirement['value'])
                    ]);
                    $successCount++;
                } catch (\Exception $e) {
                    // Continue with other updates
                }
            }
        }

        if ($successCount > 0) {
            Toaster::success("{$successCount} requirements updated successfully!");
            $this->loadRequirements(); // Refresh setelah save all
        }

        return redirect()->route('requirements.position');
    }

    public function cancel()
    {
        return redirect()->route('requirements.position');
    }

    public function render()
    {
        return view('livewire.pages.requirements.positions.edit');
    }
}
