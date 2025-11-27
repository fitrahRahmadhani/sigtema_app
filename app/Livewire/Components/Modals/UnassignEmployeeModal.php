<?php

namespace App\Livewire\Components\Modals;

use Livewire\Component;
use App\Models\Position;
use Masmerise\Toaster\Toaster;

class UnassignEmployeeModal extends Component
{
    public $show = false;
    public $positionId = null;
    public $positionName = '';
    public $employeeName = '';
    public $employeeDetails = '';

    protected $listeners = ['openUnassignEmployeeModal' => 'open'];

    public function open($positionId, $companyId = null)
    {
        $position = Position::with('employee')->find($positionId);

        if (!$position) {
            Toaster::error('Position not found.');
            return;
        }

        if (!$position->employee) {
            Toaster::error('This position is already vacant.');
            return;
        }

        $this->positionId = $positionId;
        $this->positionName = $position->name;
        $this->employeeName = $position->employee->name;
        $this->employeeDetails = $position->getWorkUnitAttribute();

        $this->show = true;
    }

    public function close()
    {
        $this->show = false;
        $this->resetModal();
    }

    public function unassign()
    {
        try {
            $position = Position::find($this->positionId);

            if (!$position) {
                Toaster::error('Position not found.');
                return;
            }

            // Simpan nama employee untuk message
            $employeeName = $position->employee ? $position->employee->name : 'Unknown Employee';

            // Unassign employee dengan set employee_id menjadi null
            $position->update(['employee_id' => null]);

            Toaster::success("Employee {$employeeName} has been unassigned from {$this->positionName}.");

            $this->close();

            // Emit event untuk refresh chart
            $this->dispatch('employeeUnassigned');
        } catch (\Exception $e) {
            Toaster::error('Failed to unassign employee: ' . $e->getMessage());
        }
    }

    private function resetModal()
    {
        $this->positionId = null;
        $this->positionName = '';
        $this->employeeName = '';
        $this->employeeDetails = '';
    }

    public function render()
    {
        return view('livewire.components.modals.unassign-employee-modal');
    }
}
