<?php

namespace App\Livewire\Pages\Positions;

use App\Livewire\Traits\HandleException;
use App\Livewire\Traits\UnauthorizedAccess;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Create Position')]
class Create extends Component
{
    use UnauthorizedAccess, HandleException;

    public User $user;
    public $name = '';
    public $position_code = '';
    public $job_grade = '';
    public $band = '';
    public $work_unit = '';
    public $group = '';
    public $komp = '';
    public $company_id = '';
    public $directorate = '';
    public $department = '';
    public $biro = '';
    public $section = '';
    public $location = '';
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

    public function mount()
    {
        $this->user = Auth::user();
        $this->isSuperadmin = $this->user->hasRole('superadmin');

        // Ambil company_id dari session untuk superadmin, atau dari user untuk admin biasa
        if ($this->isSuperadmin) {
            $companyId = session('positions_company_id');
            if (!$companyId) {
                // Redirect ke index jika belum ada company di session
                return redirect()->route('positions.index');
            }
        } else {
            $companyId = $this->user->company_id;
        }

        $this->selectedCompany = Company::find($companyId);
        $this->company_id = $companyId;

        $this->bandOptions = range(1, 5);
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'position_code' => 'required|string|max:50|unique:positions,position_code',
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

    public function message()
    {
        return [
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a string.',
            'name.max' => 'Name must not exceed 255 characters.',
            'position_code.required' => 'Position code is required.',
            'position_code.string' => 'Position code must be a string.',
            'position_code.max' => 'Position code must not exceed 50 characters.',
            'position_code.unique' => 'Position code already exists.',
            'job_grade.required' => 'Job grade is required.',
            'job_grade.numeric' => 'Job grade must be a number.',
            'job_grade.min' => 'Job grade must be at least 1.',
            'job_grade.max' => 'Job grade must not exceed 99.',
            'band.numeric' => 'Band must be a number.',
            'band.min' => 'Band must be at least 1.',
            'band.max' => 'Band must not exceed 5.',
            'work_unit.string' => 'Work unit must be a string.',
            'work_unit.max' => 'Work unit must not exceed 255 characters.',
            'group.string' => 'Group must be a string.',
            'group.max' => 'Group must not exceed 255 characters.',
            'komp.string' => 'KOMP must be a string.',
            'komp.max' => 'KOMP must not exceed 255 characters.',
            'company_id.required' => 'Company is required.',
            'company_id.exists' => 'Selected company does not exist.',
            'directorate.string' => 'Directorate must be a string.',
            'directorate.max' => 'Directorate must not exceed 255 characters.',
            'department.string' => 'Department must be a string.',
            'department.max' => 'Department must not exceed 255 characters.',
            'biro.string' => 'Biro must be a string.',
            'biro.max' => 'Biro must not exceed 255 characters.',
            'section.string' => 'Section must be a string.',
            'section.max' => 'Section must not exceed 255 characters.',
            'location.string' => 'Location must be a string.',
            'location.max' => 'Location must not exceed 255 characters.',
            'employee_id.exists' => 'Selected employee does not exist.',
            'employee_id.required' => 'Please select an employee.',
        ];
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

    public function save()
    {
        try {
            $this->validate();
            $this->authorize('create', [Position::class, $this->user->company]);

            DB::beginTransaction();

            $position = Position::create([
                'company_id' => (int)$this->company_id,
                'slug' => Str::slug($this->name) . '-' . Str::random(6),
                'position_code' => $this->position_code,
                'name' => $this->name,
                'directorate' => $this->directorate,
                'department' => $this->department,
                'biro' => $this->biro,
                'section' => $this->section,
                'job_grade' => (int)$this->job_grade,
                'band' => $this->band ? (int)$this->band : null,
                'work_unit' => $this->work_unit,
                'group' => $this->group,
                'location' => $this->location,
                'komp' => $this->komp,
            ]);

            if ($this->employee_id) {
                $position->update(['employee_id' => (int)$this->employee_id]);
            }

            DB::commit();

            return redirect()->route('positions.index')->success('Position record has been added successfully.');
        } catch (ValidationException $e) {
            DB::rollBack();
            Toaster::error('Validation failed: ' . $e->getMessage());
            throw $e;
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return $this->unauthorizedAccess('create position');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException('create position', $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('positions.index');
    }

    public function render()
    {
        return view('livewire.pages.positions.create');
    }
}
