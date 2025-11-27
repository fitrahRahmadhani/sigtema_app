<?php

namespace App\Livewire\Components\Modals;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Position;
use Masmerise\Toaster\Toaster;

class AssignEmployeeModal extends Component
{
    public $show = false;
    public $positionId = null;
    public $positionName = '';
    public $companyId = null;

    public $selectedEmployeeId = '';
    public $availableEmployees = [];

    protected $listeners = ['openAssignEmployeeModal' => 'open'];

    public function open($positionId, $companyId = null)
    {
        $position = Position::find($positionId);

        if (!$position) {
            Toaster::error('Position not found.');
            return;
        }

        // Cek jika position sudah ada employeenya
        if ($position->employee_id) {
            Toaster::error('This position already has an assigned employee.');
            return;
        }

        $this->positionId = $positionId;
        $this->positionName = $position->name;
        $this->companyId = $companyId;

        $this->loadAvailableEmployees();
        $this->show = true;
    }

    public function close()
    {
        $this->show = false;
        $this->resetModal();
    }

    private function loadAvailableEmployees()
    {
        if (!$this->companyId) {
            $this->availableEmployees = [];
            return;
        }

        // Ambil employees yang belum punya position (belum di-assign)
        $this->availableEmployees = Employee::where('company_id', $this->companyId)
            ->whereDoesntHave('position') // Employee yang belum punya position
            ->select('id', 'name', 'employee_number', 'email')
            ->orderBy('name')
            ->get()
            ->toArray();

        // Reset selected employee
        $this->selectedEmployeeId = '';
    }

    public function assign()
    {
        $this->validate([
            'selectedEmployeeId' => 'required|exists:employees,id',
        ]);

        try {
            $position = Position::find($this->positionId);
            $employee = Employee::find($this->selectedEmployeeId);

            if (!$position) {
                Toaster::error('Position not found.');
                return;
            }

            if (!$employee) {
                Toaster::error('Employee not found.');
                return;
            }

            // Cek jika employee sudah di-assign ke position lain
            $existingPosition = Position::where('employee_id', $this->selectedEmployeeId)->first();
            if ($existingPosition) {
                Toaster::error('This employee is already assigned to another position: ' . $existingPosition->name);
                return;
            }

            // Assign employee ke position
            $position->update(['employee_id' => $this->selectedEmployeeId]);

            Toaster::success("Employee {$employee->name} has been assigned to {$this->positionName}.");

            $this->close();

            // Emit event untuk refresh chart
            $this->dispatch('employeeAssigned');
        } catch (\Exception $e) {
            Toaster::error('Failed to assign employee: ' . $e->getMessage());
        }
    }

    private function resetModal()
    {
        $this->positionId = null;
        $this->positionName = '';
        $this->companyId = null;
        $this->selectedEmployeeId = '';
        $this->availableEmployees = [];
    }

    public function render()
    {
        return view('livewire.components.modals.assign-employee-modal');
    }
}
