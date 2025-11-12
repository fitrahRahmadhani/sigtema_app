<?php

namespace App\Livewire\Pages\Positions;

use App\Models\Position;
use App\Models\Company;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Masmerise\Toaster\Toaster;

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

    public function mount(Position $position)
    {
        $this->position = $position;

        // Set nilai form dari model
        $this->name = $position->name;
        $this->position_code = $position->position_code;
        $this->job_grade = $position->job_grade;
        $this->company_id = $position->company_id;
        $this->directorate = $position->directorate;
        $this->department = $position->department;
        $this->biro = $position->biro;
        $this->section = $position->section;
        $this->location = $position->location;
    }

    public function save()
    {
        $this->validate();

        try {
            $this->position->update([
                'name' => $this->name,
                'position_code' => $this->position_code,
                'job_grade' => $this->job_grade,
                'company_id' => $this->company_id,
                'directorate' => $this->directorate,
                'department' => $this->department,
                'biro' => $this->biro,
                'section' => $this->section,
                'location' => $this->location,
            ]);

            Toaster::success('Position updated successfully');
            return redirect()->route('positions.index');
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
