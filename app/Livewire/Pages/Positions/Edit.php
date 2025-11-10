<?php

namespace App\Livewire\Pages\Positions;

use App\Models\Position;
use App\Models\Company;
use App\Models\WorkUnit;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Illuminate\Support\Str;
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
    public $band = '';

    #[Validate('required|string|max:50')]
    public $job_grade = '';

    #[Validate('required|exists:companies,id')]
    public $company_id = '';

    #[Validate('required|exists:work_units,id')]
    public $work_unit_id = '';

    public $slug = '';
    public $workUnits = [];

    public function mount(Position $position)
    {
        $this->position = $position;
        $this->name = $position->name;
        $this->position_code = $position->position_code;
        $this->band = $position->band;
        $this->job_grade = $position->job_grade;
        $this->company_id = $position->company_id;
        $this->work_unit_id = $position->work_unit_id;
        $this->slug = $position->slug;

        // Load work units for the selected company
        if ($this->company_id) {
            $this->workUnits = WorkUnit::where('company_id', $this->company_id)
                ->with('departemen')
                ->orderBy('name')
                ->get();
        }
    }

    public function updatedName()
    {
        $this->slug = Str::slug($this->name);
    }

    public function updatedCompanyId()
    {
        $this->work_unit_id = '';

        if ($this->company_id) {
            $this->workUnits = WorkUnit::where('company_id', $this->company_id)
                ->with('departemen')
                ->orderBy('name')
                ->get();
        } else {
            $this->workUnits = collect();
        }
    }

    public function save()
    {
        $this->validate();

        try {
            $slug = Str::slug($this->name);

            if ($slug !== $this->position->slug) {
                $originalSlug = $slug;
                $count = 1;

                while (Position::where('slug', $slug)->where('id', '!=', $this->position->id)->exists()) {
                    $slug = $originalSlug . '-' . $count;
                    $count++;
                }
            } else {
                $slug = $this->position->slug;
            }

            $this->position->update([
                'name' => $this->name,
                'position_code' => $this->position_code,
                'band' => $this->band,
                'job_grade' => $this->job_grade,
                'company_id' => $this->company_id,
                'work_unit_id' => $this->work_unit_id,
                'slug' => $slug,
            ]);

            return redirect()->route('positions.index')->success('Position created successfully');
        } catch (\Exception $e) {
            Toaster::error('Failed to update position: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('positions.index')->success('Position created successfully');
    }

    public function render()
    {
        $companies = Company::orderBy('name')->get();

        return view('livewire.pages.positions.edit', [
            'companies' => $companies,
        ]);
    }
}
