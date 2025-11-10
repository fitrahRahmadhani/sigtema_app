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
#[Title('Create Position')]
class Create extends Component
{
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

    public $workUnits = [];

    public function mount()
    {
        $this->workUnits = collect();
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
            Position::create([
                'name' => $this->name,
                'position_code' => $this->position_code,
                'band' => $this->band,
                'job_grade' => $this->job_grade,
                'company_id' => $this->company_id,
                'work_unit_id' => $this->work_unit_id,
                'slug' => Str::uuid(),
            ]);

            return redirect()->route('positions.index')->success('Position created successfully');
        } catch (\Exception $e) {
            Toaster::error('Failed to create position: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('positions.index')->success('Position created successfully');
    }

    public function render()
    {
        $companies = Company::orderBy('name')->get();

        return view('livewire.pages.positions.create', [
            'companies' => $companies,
        ]);
    }
}
