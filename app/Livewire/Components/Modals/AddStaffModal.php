<?php

namespace App\Livewire\Components\Modals;

use Livewire\Component;
use App\Models\Company;
use App\Models\Position;
use App\Models\EmployeeHierarchy;
use Masmerise\Toaster\Toaster;

class AddStaffModal extends Component
{
    public $show = false;
    public $selectedSuperiorId = null;
    public $selectedSuperiorName = '';

    public $form = [
        'company_id' => '',
        'position_id' => '',
        'superior_id' => '',
        'relation_type' => 'direct'
    ];

    public $companies;
    public $positions = [];

    protected $listeners = [
        'openAddStaffModal' => 'open',
        'refreshPositions' => 'refreshPositions'
    ];

    public function mount()
    {
        $this->companies = Company::where('is_active', true)->get();
    }

    public function open($superiorPositionId = null)
    {
        $this->selectedSuperiorId = $superiorPositionId;
        $this->form['superior_id'] = $superiorPositionId;

        // Ambil nama superior
        if ($superiorPositionId) {
            $superior = Position::find($superiorPositionId);
            $this->selectedSuperiorName = $superior ?
                $superior->name . ' - ' . ($superior->getWorkUnitAttribute() ?? 'No Department') :
                'Unknown Position';
        } else {
            $this->selectedSuperiorName = 'No Superior Selected';
        }

        $this->show = true;
        $this->loadAvailablePositions();
    }

    public function close()
    {
        $this->show = false;
        $this->resetForm();
        $this->selectedSuperiorName = '';

        // Emit event bahwa modal ditutup
        $this->dispatch('addStaffModalClosed');
    }

    private function loadAvailablePositions()
    {
        if ($this->form['company_id']) {
            $existingChildPositionIds = EmployeeHierarchy::where('company_id', $this->form['company_id'])
                ->pluck('position_id')
                ->toArray();

            $this->positions = Position::where('company_id', $this->form['company_id'])
                ->whereNotIn('id', $existingChildPositionIds)
                ->get();
        } else {
            $this->positions = [];
        }

        $this->form['position_id'] = '';
    }

    public function refreshPositions()
    {
        $this->loadAvailablePositions();
    }

    public function updatedFormCompanyId()
    {
        $this->loadAvailablePositions();
    }

    public function save()
    {
        $this->validate([
            'form.company_id' => 'required|exists:companies,id',
            'form.position_id' => [
                'required',
                'exists:positions,id',
                'different:form.superior_id',
                function ($attribute, $value, $fail) {
                    $exists = EmployeeHierarchy::where('company_id', $this->form['company_id'])
                        ->where('position_id', $value)
                        ->exists();

                    if ($exists) {
                        $fail('This position already has a hierarchy relationship.');
                    }
                }
            ],
            'form.superior_id' => 'required|exists:positions,id|different:form.position_id',
            'form.relation_type' => 'required|string|max:255',
        ]);

        // Check if hierarchy already exists
        $exists = EmployeeHierarchy::where('company_id', $this->form['company_id'])
            ->where('position_id', $this->form['position_id'])
            ->where('superior_id', $this->form['superior_id'])
            ->exists();

        if ($exists) {
            Toaster::error('This hierarchy relationship already exists.');
            return;
        }

        try {
            EmployeeHierarchy::create([
                'company_id' => $this->form['company_id'],
                'position_id' => $this->form['position_id'],
                'superior_id' => $this->form['superior_id'],
                'relation_type' => $this->form['relation_type'],
            ]);

            Toaster::success('Staff hierarchy created successfully.');
            $this->close();

            // Emit event ke parent component untuk refresh chart
            $this->dispatch('staffAdded');

        } catch (\Exception $e) {
            Toaster::error('Failed to create staff hierarchy: ' . $e->getMessage());
        }
    }

    private function resetForm()
    {
        $this->form = [
            'company_id' => '',
            'position_id' => '',
            'superior_id' => $this->selectedSuperiorId,
            'relation_type' => 'direct'
        ];
    }

    public function render()
    {
        return view('livewire.components.modals.add-staff-modal');
    }
}
