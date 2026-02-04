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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.app')]
#[Title('Edit Position')]
class Edit extends Component
{
    public Position $position;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|string|max:50')]
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

    public $employees = [];
    public $employee_id;
    public $companies = [];

    // Band options
    public $bandOptions = [];

    public function mount(Position $position)
    {
        $this->position = $position;

        // Set nilai dari database
        $this->name = $position->name;
        $this->position_code = $position->position_code;
        $this->job_grade = (string)$position->job_grade;
        $this->band = $position->band ? (string)$position->band : '';
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

        // Load companies based on role
        $user = Auth::user();
        if ($user->hasRole('superadmin')) {
            $this->companies = Company::where('is_active', true)
                ->orderBy('name')
                ->get()
                ->map(function ($company) {
                    return [
                        'value' => $company->id,
                        'text' => $company->name . ' (' . $company->cocd . ')'
                    ];
                })
                ->toArray();
        } else {
            $company = Company::find($user->company_id);
            if ($company) {
                $this->companies = [[
                    'value' => $company->id,
                    'text' => $company->name . ' (' . $company->cocd . ')'
                ]];
            }
        }

        // Band options
        $this->bandOptions = range(1, 5);

        // Load employees
        $this->loadEmployees();
    }

    public function updatedCompanyId($value)
    {
        $this->employees = [];
        $this->employee_id = null;

        if ($value) {
            $this->loadEmployees();
        }

        // Dispatch event untuk update Select2
        $this->dispatch('companyUpdated');
    }

    private function loadEmployees()
    {
        if (!$this->company_id) return;

        $company = Company::find($this->company_id);
        if (!$company) return;

        $query = Employee::where('company_id', $this->company_id);

        // Include current employee jika ada
        if ($this->position->employee_id) {
            $query->orWhere('id', $this->position->employee_id);
        }

        $this->employees = $query->orderBy('name')
            ->get()
            ->map(function ($employee) {
                return [
                    'value' => $employee->id,
                    'text' => $employee->name . ' (' . $employee->employee_id . ')'
                ];
            })
            ->toArray();
    }

    public function save()
    {
        // Validation dengan exclude current position
        $this->validate([
            'name' => 'required|string|max:255',
            'position_code' => 'required|string|max:50|unique:positions,position_code,' . $this->position->id,
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
        ]);

        // Additional security validation
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
                'company_id' => (int)$this->company_id,
                'directorate' => $this->directorate,
                'department' => $this->department,
                'biro' => $this->biro,
                'section' => $this->section,
                'location' => $this->location,
                'employee_id' => $this->employee_id ? (int)$this->employee_id : null,
            ]);

            DB::commit();

            Toaster::success('Position updated successfully');
            return redirect()->route('positions.index');
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
