<?php

namespace App\Livewire\Pages\Educations;

use App\Livewire\Traits\HandleException;
use App\Livewire\Traits\UnauthorizedAccess;
use App\Models\Company;
use App\Models\Education;
use App\Models\Employee;
use App\Models\User;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Add Education')]
class Create extends Component
{
    use UnauthorizedAccess, HandleException;

    public User $user;

    public $employee_id;
    public $institution_name;
    public $major;
    public $degree;
    public $start_date;
    public $end_date;

    public $company_id = '';
    public $selectedCompany = null;

    // Employee search properties
    public $employeeSearch = '';
    public $showEmployeeDropdown = false;
    public $selectedEmployee = null;
    public $highlightedIndex = 0;

    public $isSuperadmin = false;

    // Minimum karakter untuk pencarian
    private $minSearchLength = 5;

    public function mount()
    {
        $this->user = Auth::user();
        $this->isSuperadmin = $this->user->hasRole('superadmin');

        // Ambil company_id dari session untuk superadmin, atau dari user untuk admin biasa
        if ($this->isSuperadmin) {
            $companyId = session('educations_company_id');
            if (!$companyId) {
                // Redirect ke index jika belum ada company di session
                return redirect()->route('educations.index');
            }
        } else {
            $companyId = $this->user->company_id;
        }

        $this->selectedCompany = Company::find($companyId);
        $this->company_id = $companyId;
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
            'employee_id' => 'required|exists:employees,id',
            'institution_name' => 'required|string|max:255',
            'major' => 'required|string|max:255',
            'degree' => 'required|string|max:100',
            'start_date' => 'required|date|before_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];
    }

    public function messages()
    {
        return [
            'employee_id.required' => 'Please select an employee.',
            'employee_id.exists' => 'Selected employee is invalid.',
            'institution_name.required' => 'Institution name is required.',
            'major.required' => 'Major/Field of study is required.',
            'degree.required' => 'Degree level is required.',
            'start_date.required' => 'Start date is required.',
            'start_date.before_or_equal' => 'Start date cannot be in the future.',
            'end_date.after_or_equal' => 'End date must be after or equal to start date.',
        ];
    }

    public function save()
    {
        try {
            $this->validate();
            $this->authorize('create', [Education::class, $this->user->company]);

            DB::beginTransaction();

            Education::create([
                'employee_id' => $this->employee_id,
                'institution_name' => $this->institution_name,
                'major' => $this->major,
                'degree' => $this->degree,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
            ]);

            DB::commit();

            return redirect()->route('educations.index')->success('Education record has been added successfully.');
        } catch (ValidationException $e) {
            DB::rollBack();
            Toaster::error('Validation failed: ' . $e->getMessage());
            throw $e;
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return $this->unauthorizedAccess('create education');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException('create education', $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('educations.index');
    }

    public function render()
    {
        return view('livewire.pages.educations.create');
    }
}
