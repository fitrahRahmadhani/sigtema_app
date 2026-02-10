<?php

namespace App\Livewire\Pages\PsychogramResults;

use App\Models\PsychogramResult;
use App\Models\Employee;
use App\Models\Company;
use App\Models\PsychogramAspect;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Create Psychogram Result')]
class Create extends Component
{
    #[Validate('required|exists:companies,id')]
    public $company_id = '';

    #[Validate('required|exists:employees,id')]
    public $employee_id = '';

    public $aspects = [];
    public $availableAspects = [];
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

        // Set company_id based on user role
        if ($this->isSuperadmin) {
            $companyId = session('psychogram_results_company_id');

            if (!$companyId) {
                Toaster::error('Please select a company first.');
                return redirect()->route('psychogram-results.index');
            }
        } else {
            // Admin uses their assigned company
            $companyId = $user->company_id;
        }

        // Load selected company
        $this->selectedCompany = Company::find($companyId);

        if (!$this->selectedCompany) {
            Toaster::error('Company not found.');
            return redirect()->route('psychogram-results.index');
        }

        $this->company_id = $companyId;

        // Initialize aspects with default row
        $this->aspects = [
            ['aspect_id' => '', 'score' => 3]
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
            ->limit(20)
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
            $this->loadAvailableAspects();
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
        $this->aspects = [['aspect_id' => '', 'score' => 3]];
        $this->availableAspects = [];
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

    private function loadAvailableAspects()
    {
        if (!$this->employee_id) {
            $this->availableAspects = [];
            return;
        }

        $employee = Employee::find($this->employee_id);

        if ($employee && $employee->company_id) {
            $this->availableAspects = PsychogramAspect::where('is_active', true)
                ->where(function ($query) use ($employee) {
                    $query->whereNull('company_id')
                        ->orWhere('company_id', $employee->company_id);
                })
                ->orderBy('aspect_name')
                ->get();
        } else {
            $this->availableAspects = PsychogramAspect::where('is_active', true)
                ->whereNull('company_id')
                ->orderBy('aspect_name')
                ->get();
        }
    }

    public function addAspect()
    {
        if (!$this->employee_id) {
            Toaster::warning('Please select an employee first.');
            return;
        }

        $this->aspects[] = ['aspect_id' => '', 'score' => 3];
    }

    public function removeAspect($index)
    {
        if (count($this->aspects) > 1) {
            unset($this->aspects[$index]);
            $this->aspects = array_values($this->aspects);
        }
    }

    public function save()
    {
        $this->validate([
            'company_id' => 'required|exists:companies,id',
            'employee_id' => 'required|exists:employees,id',
            'aspects' => 'required|array|min:1',
            'aspects.*.aspect_id' => 'required|exists:psychogram_aspects,id|distinct',
            'aspects.*.score' => 'required|integer|min:1|max:5',
        ], [
            'aspects.*.aspect_id.distinct' => 'Each aspect can only be selected once.',
            'aspects.*.aspect_id.required' => 'Please select an aspect.',
            'aspects.*.score.required' => 'Please provide a score.',
            'aspects.*.score.min' => 'Score must be at least 1.',
            'aspects.*.score.max' => 'Score must not exceed 5.',
        ]);

        try {
            $employee = Employee::find($this->employee_id);
            $errors = [];

            // Validate each aspect
            foreach ($this->aspects as $index => $aspectData) {
                // Check if combination already exists
                $exists = PsychogramResult::where('employee_id', $this->employee_id)
                    ->where('aspect_id', $aspectData['aspect_id'])
                    ->exists();

                if ($exists) {
                    $this->addError(
                        "aspects.{$index}.aspect_id",
                        'A score for this aspect already exists for this employee.'
                    );
                    $errors[] = true;
                    continue;
                }

                // Validate aspect availability
                $aspect = PsychogramAspect::find($aspectData['aspect_id']);

                if ($aspect->company_id && $aspect->company_id != $employee->company_id) {
                    $this->addError(
                        "aspects.{$index}.aspect_id",
                        'This aspect is not available for this employee\'s company.'
                    );
                    $errors[] = true;
                    continue;
                }
            }

            if (!empty($errors)) {
                Toaster::error('Please fix the errors before submitting.');
                return;
            }

            // Create all results
            foreach ($this->aspects as $aspectData) {
                PsychogramResult::create([
                    'employee_id' => $this->employee_id,
                    'aspect_id' => $aspectData['aspect_id'],
                    'score' => $aspectData['score'],
                ]);
            }

            Toaster::success('Psychogram results created successfully.');
            return redirect()->route('psychogram-results.index');
        } catch (\Exception $e) {
            Toaster::error('Failed to create psychogram results: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('psychogram-results.index');
    }

    public function render()
    {
        return view('livewire.pages.psychogram-results.create');
    }
}
