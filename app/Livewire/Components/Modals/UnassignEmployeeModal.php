<?php

namespace App\Livewire\Components\Modals;

use App\Models\Position;
use App\Models\PositionHistory;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class UnassignEmployeeModal extends Component
{
    public $show = false;
    public $positionId = null;

    // Save as history
    public $saveAsHistory   = false;
    public $historyEmployeeName = '';
    public $historyPositionName = '';
    public $historyWorkUnit     = '';
    public $historyCompany      = '';
    public $historyStartDate    = '';
    public $historyEndDate      = '';

    // For display only
    public $positionName    = '';
    public $employeeName    = '';

    protected $listeners = ['openUnassignEmployeeModal' => 'open'];

    protected function rules(): array
    {
        if ($this->saveAsHistory) {
            return [
                'historyEmployeeName' => ['required', 'string', 'max:255'],
                'historyPositionName' => ['required', 'string', 'max:255'],
                'historyWorkUnit'     => ['nullable', 'string', 'max:255'],
                'historyCompany'      => ['nullable', 'string', 'max:255'],
                'historyStartDate'    => ['nullable', 'date'],
                'historyEndDate'      => ['nullable', 'date', 'after_or_equal:historyStartDate'],
            ];
        }
        return [];
    }

    protected function messages(): array
    {
        return [
            'historyEmployeeName.required' => 'Employee name is required.',
            'historyPositionName.required' => 'Position name is required.',
            'historyEndDate.after_or_equal' => 'End date must be after or equal to start date.',
        ];
    }

    public function open($positionId, $companyId = null)
    {
        $position = Position::with(['employee', 'employee.company'])->find($positionId);

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

        // Pre-fill history fields
        $this->historyEmployeeName = $position->employee->name;
        $this->historyPositionName = $position->name;
        $this->historyWorkUnit     = $position->work_unit ?? '';
        $this->historyCompany      = $position->employee->company->name ?? '';
        $this->historyStartDate    = $position->employee->created_at->format('Y-m-d') ?? '';
        $this->historyEndDate      = now()->format('Y-m-d');

        $this->saveAsHistory = false;
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
            $this->validate();
            $this->authorize('create', [PositionHistory::class, Auth::user()?->company]);
            $position = Position::with('employee')->find($this->positionId);

            if (!$position) {
                Toaster::error('Position not found.');
                return;
            }

            $employee      = $position->employee;
            $employeeName  = $employee ? $employee->name : 'Unknown Employee';

            DB::beginTransaction();

            // Save history if checked
            if ($this->saveAsHistory && $employee) {
                PositionHistory::create([
                    'employee_id'   => $employee->id,
                    'position_name' => $this->historyPositionName,
                    'work_unit'     => $this->historyWorkUnit ?: null,
                    'company'       => $this->historyCompany ?: null,
                    'start_date'    => $this->historyStartDate ?: null,
                    'end_date'      => $this->historyEndDate ?: null,
                ]);
            }

            $position->update(['employee_id' => null]);

            DB::commit();

            Toaster::success("Employee {$employeeName} has been unassigned from {$this->positionName}.");

            $this->close();
            $this->dispatch('employeeUnassigned');
        } catch (ValidationException $e) {
            DB::rollBack();
            Toaster::error('Validation failed: ' . $e->getMessage());
            throw $e;
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return $this->unauthorizedAccess('unassign employee');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException('unassign employee', $e->getMessage());
        }
    }

    private function resetModal()
    {
        $this->positionId        = null;
        $this->positionName      = '';
        $this->employeeName      = '';
        $this->saveAsHistory     = false;
        $this->historyEmployeeName = '';
        $this->historyPositionName = '';
        $this->historyWorkUnit   = '';
        $this->historyCompany    = '';
        $this->historyStartDate  = '';
        $this->historyEndDate    = '';
    }

    public function render()
    {
        return view('livewire.components.modals.unassign-employee-modal');
    }
}
