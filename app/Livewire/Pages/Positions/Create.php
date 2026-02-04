<?php

namespace App\Livewire\Pages\Positions;

use App\Models\Company;
use Livewire\Component;
use App\Models\Position;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Masmerise\Toaster\Toaster;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.app')]
#[Title('Create Position')]
class Create extends Component
{
    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|string|max:50|unique:positions,position_code')]
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

    public $employees = [];
    public $companies = [];
    public $bandOptions = [];

    public function mount()
    {
        $user = Auth::user();

        // Load companies based on role
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
            $this->company_id = $user->company_id;
            $company = Company::find($user->company_id);
            if ($company) {
                $this->companies = [[
                    'value' => $company->id,
                    'text' => $company->name . ' (' . $company->cocd . ')'
                ]];
            }
        }

        $this->bandOptions = range(1, 5);

        if ($this->company_id) {
            $this->loadEmployees();
        }
    }

    public function updatedCompanyId($value)
    {
        $this->employees = [];
        $this->employee_id = null;

        if ($value) {
            $this->loadEmployees();
        }

        $this->dispatch('companyUpdated');
    }

    protected function loadEmployees()
    {
        $company = Company::find($this->company_id);
        if (!$company) return;

        $this->employees = $company->employees()
            ->orderBy('name')
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
        $this->validate();

        if ($this->employee_id) {
            $exists = \App\Models\Employee::where('id', $this->employee_id)
                ->where('company_id', $this->company_id)
                ->exists();

            if (!$exists) {
                $this->addError('employee_id', 'Selected employee does not belong to the selected company.');
                return;
            }
        }

        try {
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

            return redirect()->route('positions.index')->success('Position created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Toaster::error('Failed to create position: ' . $e->getMessage());
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
