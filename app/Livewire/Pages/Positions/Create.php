<?php

namespace App\Livewire\Pages\Positions;

use App\Models\Company;
use Livewire\Component;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Masmerise\Toaster\Toaster;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

#[Layout('components.layouts.app')]
#[Title('Create Position')]
class Create extends Component
{
    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|string|max:50')]
    public $position_code = '';

    #[Validate('required|string|max:50')]
    public $job_grade = '';

    #[Validate('required|exists:companies,id')]
    public $company_id = '';

    // Field baru berdasarkan skema database
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

    public function mount() {}

    public function updatedCompanyId()
    {
        $company = Company::find($this->company_id);
        $this->employees = $company->employees ?? [];
    }

    public function save()
    {
        $this->validate();

        try {
            $position = Position::create([
                'name' => $this->name,
                'position_code' => $this->position_code,
                'job_grade' => $this->job_grade,
                'company_id' => $this->company_id,
                'directorate' => $this->directorate,
                'department' => $this->department,
                'biro' => $this->biro,
                'section' => $this->section,
                'location' => $this->location,
                'slug' => Str::slug($this->name . '-' . Str::random(6)),
            ]);

            if ($this->employee_id) {
                $position->update(['employee_id' => $this->employee_id]);
            }

            return redirect()->route('positions.index')->success('Position created successfully');
        } catch (\Exception $e) {
            Toaster::error('Failed to create position: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('positions.index');
    }

    public function render()
    {
        $companies = Company::orderBy('name')->get();

        return view('livewire.pages.positions.create', [
            'companies' => $companies,
        ]);
    }
}
