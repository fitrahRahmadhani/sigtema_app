<?php

namespace App\Livewire\Pages\Burnouts;

use App\Models\Burnout;
use App\Models\Employee;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Masmerise\Toaster\Toaster;
use App\Livewire\Traits\HandleException;
use App\Livewire\Traits\UnauthorizedAccess;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;

#[Layout('components.layouts.app')]
#[Title('Create Burnout')]
class Create extends Component
{
    use UnauthorizedAccess, HandleException;

    public $employee_id = '';
    public $employee_number = '';
    public $emotional_exhaustion = '';
    public $depersonalization = '';
    public $achievement_decline = '';
    public $total = '';
    public $level = '';
    public $description = '';
    public $recommendation = '';
    public $result = '';
    public $is_active = true;

    // Available options
    public $employees = [];

    // Employee search properties
    public $employeeSearch = '';
    public $showEmployeeDropdown = false;
    public $selectedEmployee = null;
    public $highlightedIndex = 0;

    // Minimum karakter untuk pencarian
    private $minSearchLength = 3;

    protected function rules()
    {
        return [
            'employee_id' => ['nullable', 'exists:employees,id'],
            'employee_number' => ['nullable', 'string', 'max:100'],
            'emotional_exhaustion' => ['nullable', 'integer', 'min:0', 'max:100'],
            'depersonalization' => ['nullable', 'integer', 'min:0', 'max:100'],
            'achievement_decline' => ['nullable', 'integer', 'min:0', 'max:100'],
            'total' => ['nullable', 'integer'],
            'level' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'recommendation' => ['nullable', 'string'],
            'result' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ];
    }

    protected function messages()
    {
        return [
            'employee_id.exists' => 'Selected employee does not exist.',
            'employee_number.max' => 'Employee number cannot exceed 100 characters.',
            'emotional_exhaustion.integer' => 'Emotional Exhaustion must be a number.',
            'emotional_exhaustion.min' => 'Emotional Exhaustion must be at least 0.',
            'emotional_exhaustion.max' => 'Emotional Exhaustion cannot exceed 100.',
            'depersonalization.integer' => 'Depersonalization must be a number.',
            'depersonalization.min' => 'Depersonalization must be at least 0.',
            'depersonalization.max' => 'Depersonalization cannot exceed 100.',
            'achievement_decline.integer' => 'Achievement Decline must be a number.',
            'achievement_decline.min' => 'Achievement Decline must be at least 0.',
            'achievement_decline.max' => 'Achievement Decline cannot exceed 100.',
            'total.integer' => 'Total must be a number.',
            'level.max' => 'Level cannot exceed 100 characters.',
            'result.max' => 'Result cannot exceed 255 characters.',
        ];
    }

    public function mount()
    {
        // Load employees for selection
        $this->employees = Employee::orderBy('name')->get();
    }

    public function updatedEmployeeSearch($value)
    {
        $this->highlightedIndex = 0;

        if (strlen(trim($value)) >= $this->minSearchLength) {
            $this->showEmployeeDropdown = true;
        } else {
            $this->showEmployeeDropdown = false;
        }
    }

    public function getFilteredEmployeesProperty()
    {
        $searchTerm = trim($this->employeeSearch);

        if (strlen($searchTerm) < $this->minSearchLength) {
            return collect();
        }

        $searchTermLower = strtolower($searchTerm);

        return Employee::where(function ($query) use ($searchTermLower) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . $searchTermLower . '%'])
                    ->orWhereRaw('LOWER(employee_number) LIKE ?', ['%' . $searchTermLower . '%']);
            })
            ->orderBy('name')
            ->limit(10)
            ->get();
    }

    public function selectEmployee($employeeId)
    {
        $employee = Employee::find($employeeId);

        if ($employee) {
            $this->selectedEmployee = $employee;
            $this->employee_id = $employee->id;
            $this->employeeSearch = $employee->name . ' (' . $employee->employee_number . ')';
            $this->employee_number = $employee->employee_number;
            $this->showEmployeeDropdown = false;
            $this->highlightedIndex = 0;
        }
    }

    public function clearEmployeeSelection()
    {
        $this->selectedEmployee = null;
        $this->employee_id = null;
        $this->employeeSearch = '';
        $this->employee_number = '';
        $this->showEmployeeDropdown = false;
        $this->highlightedIndex = 0;
    }

    public function incrementHighlight()
    {
        $count = $this->filteredEmployees->count();
        if ($count > 0) {
            $this->highlightedIndex = min($this->highlightedIndex + 1, $count - 1);
        }
    }

    public function decrementHighlight()
    {
        $this->highlightedIndex = max($this->highlightedIndex - 1, 0);
    }

    public function selectHighlighted()
    {
        $employees = $this->filteredEmployees;

        if ($employees->count() > 0 && isset($employees[$this->highlightedIndex])) {
            $this->selectEmployee($employees[$this->highlightedIndex]->id);
        }
    }

    public function closeDropdown()
    {
        $this->showEmployeeDropdown = false;
    }

    public function openDropdown()
    {
        if (!$this->selectedEmployee && strlen(trim($this->employeeSearch)) >= $this->minSearchLength) {
            $this->showEmployeeDropdown = true;
        }
    }

    public function save()
    {
        try {
            // Validate form
            $this->validate();

            DB::beginTransaction();

            // Create burnout record
            Burnout::create([
                'employee_id' => $this->employee_id ?: null,
                'employee_number' => $this->employee_number ?: null,
                'emotional_exhaustion' => $this->emotional_exhaustion ?: null,
                'depersonalization' => $this->depersonalization ?: null,
                'achievement_decline' => $this->achievement_decline ?: null,
                'total' => $this->total ?: null,
                'level' => $this->level ?: null,
                'description' => $this->description ?: null,
                'recommendation' => $this->recommendation ?: null,
                'result' => $this->result ?: null,
                'source' => 'internal',
                'is_active' => $this->is_active,
            ]);

            DB::commit();

            return redirect()->route('burnouts.index')->success('Burnout record created successfully!');
        } catch (ValidationException $e) {
            DB::rollBack();
            Toaster::error('Validation failed: ' . $e->getMessage());
            throw $e;
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return $this->unauthorizedAccess('create burnout');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException('create burnout', $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('burnouts.index');
    }

    public function render()
    {
        return view('livewire.pages.burnouts.create');
    }
}