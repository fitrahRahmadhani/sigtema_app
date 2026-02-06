<?php

namespace App\Livewire\Pages\Positions;

use App\Models\Company;
use Livewire\Component;
use App\Models\Position;
use App\Models\Employee;
use Livewire\Attributes\Title;
use Masmerise\Toaster\Toaster;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.app')]
#[Title('Edit Position')]
class Edit extends Component
{
    public Position $position;

    #[Validate('required|string|max:255')]
    public $name = '';

    public $position_code = '';

    #[Validate('required|numeric|min:1|max:99')]
    public $job_grade = '';

    #[Validate('nullable|numeric|min:1|max:5')]
    public $band = '';

    #[Validate('nullable|string|max:255')]
    public $work_unit = '';

    #[Validate('nullable|string|max:255')]
    public $group = '';

    #[Validate('nullable|string|max:255')]
    public $komp = '';

    #[Validate('required|exists:companies,id')]
    public $company_id = '';

    #[Validate('nullable|string|max:255')]
    public $directorate = '';

    #[Validate('nullable|string|max:255')]
    public $department = '';

    #[Validate('nullable|string|max:255')]
    public $biro = '';

    #[Validate('nullable|string|max:255')]
    public $section = '';

    #[Validate('nullable|string|max:255')]
    public $location = '';

    #[Validate('nullable|exists:employees,id')]
    public $employee_id;

    public $selectedCompany = null;
    public $isSuperadmin = false;

    // Employee search properties
    public $employeeSearch = '';
    public $showEmployeeDropdown = false;
    public $selectedEmployee = null;
    public $highlightedIndex = 0;

    // Minimum karakter untuk pencarian
    private $minSearchLength = 5;

    public $bandOptions = [];

    public function mount(Position $position)
    {
        $this->position = $position;

        // Load data position ke form
        $this->name = $position->name;
        $this->position_code = $position->position_code;
        $this->job_grade = $position->job_grade;
        $this->band = $position->band;
        $this->work_unit = $position->work_unit;
        $this->group = $position->group;
        $this->komp = $position->komp;
        $this->company_id = $position->company_id;
        $this->directorate = $position->directorate;
        $this->department = $position->department;
        $this->biro = $position->biro;
        $this->section = $position->section;
        $this->location = $position->location;
        $this->employee_id = $position->employee_id;

        $user = Auth::user();
        $this->isSuperadmin = $user->hasRole('superadmin');

        // Validasi akses
        if (!$this->isSuperadmin && $position->company_id !== $user->company_id) {
            abort(403, 'Unauthorized access to this position.');
        }

        $this->selectedCompany = Company::find($position->company_id);
        $this->bandOptions = range(1, 5);

        // Load selected employee jika ada
        if ($this->employee_id) {
            $this->selectedEmployee = Employee::find($this->employee_id);
            if ($this->selectedEmployee) {
                $this->employeeSearch = $this->selectedEmployee->name . ' (' . $this->selectedEmployee->employee_number . ')';
            }
        }
    }

    public function updatedEmployeeSearch($value)
    {
        // Reset highlight
        $this->highlightedIndex = 0;

        // Tampilkan dropdown hanya jika ada input minimal 5 karakter
        if (strlen(trim($value)) >= $this->minSearchLength) {
            $this->showEmployeeDropdown = true;
        } else {
            $this->showEmployeeDropdown = false;
        }
    }

    public function getFilteredEmployeesProperty()
    {
        if (!$this->company_id) {
            return collect();
        }

        $searchTerm = trim($this->employeeSearch);

        // Hanya lakukan pencarian jika input >= 5 karakter
        if (strlen($searchTerm) < $this->minSearchLength) {
            return collect();
        }

        // Pencarian dengan minimal 5 karakter
        $searchTermLower = strtolower($searchTerm);

        return Employee::where('company_id', $this->company_id)
            ->where(function ($query) use ($searchTermLower) {
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
            $this->showEmployeeDropdown = false;
            $this->highlightedIndex = 0;
        }
    }

    public function clearEmployeeSelection()
    {
        $this->resetEmployeeSelection();
    }

    private function resetEmployeeSelection()
    {
        $this->selectedEmployee = null;
        $this->employee_id = null;
        $this->employeeSearch = '';
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
        if ($this->company_id && !$this->selectedEmployee && strlen(trim($this->employeeSearch)) >= $this->minSearchLength) {
            $this->showEmployeeDropdown = true;
        }
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'position_code' => [
                'required',
                'string',
                'max:50',
                'unique:positions,position_code,' . $this->position->id
            ],
            'job_grade' => 'required|numeric|min:1|max:99',
            'band' => 'nullable|numeric|min:1|max:5',
            'work_unit' => 'nullable|string|max:255',
            'group' => 'nullable|string|max:255',
            'komp' => 'nullable|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'directorate' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'biro' => 'nullable|string|max:255',
            'section' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'employee_id' => 'nullable|exists:employees,id',
        ];
    }

    public function save()
    {
        $this->validate();

        if ($this->employee_id) {
            $exists = Employee::where('id', $this->employee_id)
                ->where('company_id', $this->company_id)
                ->exists();

            if (!$exists) {
                $this->addError('employee_id', 'Selected employee does not belong to the selected company.');
                return;
            }
        }

        try {
            DB::beginTransaction();

            $this->position->update([
                'name' => $this->name,
                'position_code' => $this->position_code,
                'job_grade' => (int)$this->job_grade,
                'band' => $this->band ? (int)$this->band : null,
                'work_unit' => $this->work_unit,
                'group' => $this->group,
                'komp' => $this->komp,
                'directorate' => $this->directorate,
                'department' => $this->department,
                'biro' => $this->biro,
                'section' => $this->section,
                'location' => $this->location,
                'employee_id' => $this->employee_id ? (int)$this->employee_id : null,
            ]);

            DB::commit();

            return redirect()->route('positions.index')->success('Position updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Toaster::error('Failed to update position: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('positions.index');
    }

    public function render()
    {
        return view('livewire.pages.positions.edit');
    }
}
