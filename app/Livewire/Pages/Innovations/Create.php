<?php

namespace App\Livewire\Pages\Innovations;

use App\Models\Employee;
use App\Models\Innovation;
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
#[Title('Create Innovation')]
class Create extends Component
{
    use UnauthorizedAccess, HandleException;

    public $title = '';
    public $employee_id = '';
    public $employee_number = '';
    public $team = '';
    public $theme = '';
    public $event = '';
    public $category = '';
    public $replication = '';
    public $status = '';
    public $achievement = '';
    public $is_active = true;

    // Available options
    public $employees = [];
    public $teams = [];
    public $categories = [];
    public $replications = [];
    public $statuses = [];

    // Employee search properties
    public $employeeSearch = '';
    public $showEmployeeDropdown = false;
    public $selectedEmployee = null;
    public $highlightedIndex = 0;

    // Minimum karakter untuk pencarian
    private $minSearchLength = 3;

    // Team and Category input mode
    public $teamInputMode = 'select'; // 'select' or 'input'
    public $categoryInputMode = 'select'; // 'select' or 'input'

    protected function rules()
    {
        return [
            'title' => ['required', 'string', 'max:500'],
            'employee_id' => ['nullable', 'exists:employees,id'],
            'employee_number' => ['nullable', 'string', 'max:100'],
            'team' => ['nullable', 'string', 'max:255'],
            'theme' => ['nullable', 'string', 'max:255'],
            'event' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'replication' => ['nullable', 'string', 'in:Bisa Direplikasi,Tidak Bisa Direplikasi'],
            'status' => ['nullable', 'string', 'max:100'],
            'achievement' => ['nullable', 'string', 'max:500'],
            'is_active' => ['boolean'],
        ];
    }

    protected function messages()
    {
        return [
            'title.required' => 'Innovation title is required.',
            'title.max' => 'Innovation title cannot exceed 500 characters.',
            'employee_id.exists' => 'Selected employee does not exist.',
            'employee_number.max' => 'Employee number cannot exceed 100 characters.',
            'team.max' => 'Team name cannot exceed 255 characters.',
            'theme.max' => 'Theme name cannot exceed 255 characters.',
            'event.max' => 'Event name cannot exceed 255 characters.',
            'category.max' => 'Category cannot exceed 255 characters.',
            'replication.in' => 'Replication must be either Bisa Direplikasi ,Tidak Bisa Direplikasi.',
            'status.max' => 'Status cannot exceed 100 characters.',
            'achievement.max' => 'Achievement cannot exceed 500 characters.',
        ];
    }

    public function mount()
    {
        // Load employees for selection
        $this->employees = Employee::orderBy('name')->get();

        // Load existing values for dropdowns
        $this->teams = Innovation::whereNotNull('team')->distinct()->pluck('team')->sort()->values();
        $this->categories = Innovation::whereNotNull('category')->distinct()->pluck('category')->sort()->values();
        $this->replications = ['belum', 'proses', 'sudah'];
        $this->statuses = Innovation::whereNotNull('status')->distinct()->pluck('status')->sort()->values();
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

    public function toggleTeamInputMode()
    {
        $this->teamInputMode = $this->teamInputMode === 'select' ? 'input' : 'select';
    }

    public function toggleCategoryInputMode()
    {
        $this->categoryInputMode = $this->categoryInputMode === 'select' ? 'input' : 'select';
    }

    public function save()
    {
        try {
            // Validate form
            $this->validate();

            DB::beginTransaction();

            // Create innovation
            $innovation = Innovation::create([
                'employee_id' => $this->employee_id ?: null,
                'employee_number' => $this->employee_number ?: null,
                'title' => $this->title,
                'team' => $this->team ?: null,
                'theme' => $this->theme ?: null,
                'event' => $this->event ?: null,
                'category' => $this->category ?: null,
                'replication' => $this->replication ?: null,
                'status' => $this->status ?: null,
                'achievement' => $this->achievement ?: null,
                'source' => 'internal',
                'is_active' => $this->is_active,
            ]);

            DB::commit();

            return redirect()->route('innovations.index')->success('Innovation created successfully!');
        } catch (ValidationException $e) {
            DB::rollBack();
            Toaster::error('Validation failed: ' . $e->getMessage());
            throw $e;
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return $this->unauthorizedAccess('create innovation');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException('create innovation', $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('innovations.index');
    }

    public function render()
    {
        return view('livewire.pages.innovations.create');
    }
}