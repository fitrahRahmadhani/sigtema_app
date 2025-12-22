<?php

namespace App\Livewire\Pages\Positions;

use App\Models\Company;
use Livewire\Component;
use App\Models\Employee;
use App\Models\Position;
use Livewire\Attributes\Title;
use Masmerise\Toaster\Toaster;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

#[Layout('components.layouts.app')]
#[Title('Edit Position')]
class Edit extends Component
{
    public Position $position;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|string|max:50')]
    public $position_code = '';

    #[Validate('required|string|max:50')]
    public $job_grade = '';

    #[Validate('nullable|string|max:50')]
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

    public $employees = [];
    public $employee_id;

    // Band options for select dropdown
    public $bandOptions = [
        'Band 1',
        'Band 2',
        'Band 3',
        'Band 4',
        'Band 5',
    ];

    public function mount(Position $position)
    {
        $this->position = $position;

        // Set nilai dari database
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

        // Set employee data
        $this->employee_id = $position->employee_id;

        // Load employees
        $this->loadEmployees();
    }

    public function updatedCompanyId()
    {
        $this->loadEmployees();
    }

    private function loadEmployees()
    {
        // Load employees from selected company
        $this->employees = Employee::where('company_id', $this->company_id)
            ->where(function ($query) {
                // Include current employee if any
                if ($this->employee_id) {
                    $query->orWhere('id', $this->employee_id);
                }
            })
            ->orderBy('name')
            ->get();
    }

    public function save()
    {
        $this->validate();

        try {
            $this->position->update([
                'name' => $this->name,
                'position_code' => $this->position_code,
                'job_grade' => $this->job_grade,
                'band' => $this->band,
                'work_unit' => $this->work_unit,
                'group' => $this->group,
                'komp' => $this->komp,
                'company_id' => $this->company_id,
                'directorate' => $this->directorate,
                'department' => $this->department,
                'biro' => $this->biro,
                'section' => $this->section,
                'location' => $this->location,
                'employee_id' => $this->employee_id ?: null
            ]);

            return redirect()->route('positions.index')->success('Position updated successfully');
        } catch (\Exception $e) {
            Toaster::error('Failed to update position: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('positions.index');
    }

    public function render()
    {
        $companies = Company::orderBy('name')->get();

        return view('livewire.pages.positions.edit', [
            'companies' => $companies,
        ]);
    }
}
