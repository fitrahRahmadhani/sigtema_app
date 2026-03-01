<?php

namespace App\Livewire\Components\Modals;

use App\Livewire\Traits\HandleException;
use App\Livewire\Traits\UnauthorizedAccess;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class AssignEmployeeModal extends Component
{
    use UnauthorizedAccess, HandleException;

    public $show         = false;
    public $positionId   = null;
    public $positionName = '';
    public $companyId    = null;
    public $company      = null;

    // Employee search
    public $employeeSearch           = '';
    public $showEmployeeDropdown     = false;
    public $selectedEmployee         = null;
    public $selectedEmployeeId       = '';
    public $highlightedIndex         = 0;

    private $minSearchLength = 5;

    protected $listeners = ['openAssignEmployeeModal' => 'open'];

    protected function rules()
    {
        return [
            'selectedEmployeeId' => 'required|exists:employees,id',
        ];
    }

    protected function messages()
    {
        return [
            'selectedEmployeeId.required' => 'Employee is required.',
        ];
    }

    public function open($positionId, $companyId = null)
    {
        $position = Position::find($positionId);

        if (!$position) {
            Toaster::error('Position not found.');
            return;
        }

        if ($position->employee_id) {
            Toaster::error('This position already has an assigned employee.');
            return;
        }

        $this->positionId   = $positionId;
        $this->positionName = $position->name;
        $this->companyId    = $companyId ?? $position->company_id;
        $this->company      = Company::find($this->companyId);

        $this->show = true;
    }

    public function close()
    {
        $this->show = false;
        $this->resetModal();
    }

    public function getFilteredEmployeesProperty()
    {
        if (!$this->companyId || strlen(trim($this->employeeSearch)) < $this->minSearchLength) {
            return collect();
        }

        return Employee::where('company_id', $this->companyId)
            ->whereDoesntHave('position')
            ->where(function ($q) {
                $q->where('name', 'LIKE', '%' . $this->employeeSearch . '%')
                    ->orWhere('employee_number', 'LIKE', '%' . $this->employeeSearch . '%');
            })
            ->select(['id', 'name', 'employee_number', 'email'])
            ->orderBy('name')
            ->limit(10)
            ->get();
    }

    public function updatedEmployeeSearch($value)
    {
        $this->highlightedIndex  = 0;
        $this->showEmployeeDropdown = strlen(trim($value)) >= $this->minSearchLength;
    }

    public function selectEmployee(int $id): void
    {
        $employee = Employee::select(['id', 'name', 'employee_number', 'email'])->find($id);
        if ($employee) {
            $this->selectedEmployee     = $employee;
            $this->selectedEmployeeId   = $employee->id;
            $this->employeeSearch       = $employee->name . ' (' . $employee->employee_number . ')';
            $this->showEmployeeDropdown = false;
            $this->highlightedIndex     = 0;
        }
    }

    public function clearEmployeeSelection(): void
    {
        $this->selectedEmployee     = null;
        $this->selectedEmployeeId   = '';
        $this->employeeSearch       = '';
        $this->showEmployeeDropdown = false;
        $this->highlightedIndex     = 0;
    }

    public function openDropdown(): void
    {
        if ($this->companyId && !$this->selectedEmployee && strlen(trim($this->employeeSearch)) >= $this->minSearchLength) {
            $this->showEmployeeDropdown = true;
        }
    }

    public function closeDropdown(): void
    {
        $this->showEmployeeDropdown = false;
    }

    public function incrementHighlight(): void
    {
        $count = $this->filteredEmployees->count();
        if ($count > 0) {
            $this->highlightedIndex = min($this->highlightedIndex + 1, $count - 1);
        }
    }

    public function decrementHighlight(): void
    {
        $this->highlightedIndex = max($this->highlightedIndex - 1, 0);
    }

    public function selectHighlighted(): void
    {
        $employees = $this->filteredEmployees;
        if ($employees->count() > 0 && isset($employees[$this->highlightedIndex])) {
            $this->selectEmployee($employees[$this->highlightedIndex]->id);
        }
    }


    public function assign()
    {

        try {
            $this->validate();
            $position = Position::find($this->positionId);
            $employee = Employee::find($this->selectedEmployeeId);

            if (!$position) {
                Toaster::error('Position not found.');
                return;
            }

            $this->authorize('update', [Position::class, $position]);

            if (!$employee) {
                Toaster::error('Employee not found.');
                return;
            }

            $existingPosition = Position::where('employee_id', $this->selectedEmployeeId)->first();
            if ($existingPosition) {
                Toaster::error('This employee is already assigned to: ' . $existingPosition->name);
                return;
            }

            DB::beginTransaction();

            $position->update(['employee_id' => $this->selectedEmployeeId]);

            DB::commit();

            Toaster::success("Employee {$employee->name} has been assigned to {$this->positionName}.");

            $this->close();
            $this->dispatch('employeeAssigned');
        } catch (ValidationException $e) {
            DB::rollBack();
            Toaster::error('Validation failed: ' . $e->getMessage());
            throw $e;
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return $this->unauthorizedAccess('assign employee');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException('assign employee', $e->getMessage());
        }
    }

    private function resetModal()
    {
        $this->positionId           = null;
        $this->positionName         = '';
        $this->companyId            = null;
        $this->company              = null;
        $this->selectedEmployee     = null;
        $this->selectedEmployeeId   = '';
        $this->employeeSearch       = '';
        $this->showEmployeeDropdown = false;
        $this->highlightedIndex     = 0;
    }

    public function render()
    {
        return view('livewire.components.modals.assign-employee-modal');
    }
}
