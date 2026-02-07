<?php

namespace App\Livewire\Pages\Mcus;

use App\Models\Mcu;
use App\Models\Employee;
use App\Models\Company;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Masmerise\Toaster\Toaster;
use Exception;

#[Layout('components.layouts.app')]
#[Title('Add MCU Record')]
class Create extends Component
{
    public $employee_id;
    public $result;
    public $year;

    public $company_id = '';
    public $selectedCompany = null;
    public $isSuperadmin = false;

    // Employee search properties
    public $employeeSearch = '';
    public $showEmployeeDropdown = false;
    public $selectedEmployee = null;
    public $highlightedIndex = 0;

    // Minimum karakter untuk pencarian
    private $minSearchLength = 5;

    public function mount()
    {
        $user = Auth::user();
        $this->isSuperadmin = $user->hasRole('superadmin');

        // Ambil company_id dari session untuk superadmin, atau dari user untuk admin biasa
        if ($this->isSuperadmin) {
            $companyId = session('mcus_company_id');
            if (!$companyId) {
                // Redirect ke index jika belum ada company di session
                return redirect()->route('mcus.index');
            }
        } else {
            $companyId = $user->company_id;
        }

        $this->selectedCompany = Company::find($companyId);
        $this->company_id = $companyId;
        $this->year = date('Y');
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
            'company_id' => 'required|exists:companies,id',
            'employee_id' => 'required|exists:employees,id',
            'result' => 'required|in:1,2,3,4,5',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ];
    }

    public function messages()
    {
        return [
            'company_id.required' => 'Please select a company.',
            'employee_id.required' => 'Please select an employee.',
            'result.required' => 'MCU result is required.',
            'result.in' => 'Please select a valid MCU result (1-5).',
            'year.required' => 'Year is required.',
            'year.integer' => 'Year must be a valid number.',
            'year.min' => 'Year must be at least 2000.',
            'year.max' => 'Year cannot be more than ' . (date('Y') + 1) . '.',
        ];
    }

    public function save()
    {
        try {
            $this->validate();

            // Validasi bahwa employee termasuk dalam company yang dipilih
            if ($this->employee_id) {
                $exists = Employee::where('id', $this->employee_id)
                    ->where('company_id', $this->company_id)
                    ->exists();

                if (!$exists) {
                    $this->addError('employee_id', 'Selected employee does not belong to the selected company.');
                    return;
                }
            }

            Mcu::create([
                'employee_id' => $this->employee_id,
                'result' => $this->result,
                'year' => $this->year,
            ]);

            return redirect()->route('mcus.index')->success('MCU record has been added successfully.');
        } catch (Exception $e) {
            Toaster::error('Failed to add MCU record. Please try again.');
        }
    }

    public function cancel()
    {
        return redirect()->route('mcus.index');
    }

    public function render()
    {
        return view('livewire.pages.mcus.create');
    }
}
