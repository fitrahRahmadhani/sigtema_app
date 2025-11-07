<?php

namespace App\Livewire\Pages\WorkUnits;

use App\Models\WorkUnit;
use App\Models\Company;
use App\Models\Departemen;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Illuminate\Support\Str;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Create Work Unit')]
class Create extends Component
{
    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|string|max:50')]
    public $work_unit_code = '';

    #[Validate('required|exists:companies,id')]
    public $company_id = '';

    #[Validate('required|exists:departemens,id')]
    public $departemen_id = '';

    public $departments = [];

    public function mount()
    {
        $this->departments = collect();
    }

    public function updatedCompanyId()
    {
        // Reset department when company changes
        $this->departemen_id = '';

        // Load departments for selected company
        if ($this->company_id) {
            $this->departments = Departemen::where('company_id', $this->company_id)
                ->orderBy('name')
                ->get();
        } else {
            $this->departments = collect();
        }
    }

    public function save()
    {
        $this->validate();

        try {
            WorkUnit::create([
                'slug' => Str::uuid(),
                'name' => $this->name,
                'work_unit_code' => $this->work_unit_code,
                'company_id' => $this->company_id,
                'departemen_id' => $this->departemen_id,
            ]);

            return redirect()->route('work-units.index')->success('Work Unit created successfully');
        } catch (\Exception $e) {
            Toaster::error('Failed to create work unit: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('work-units.index')->success('Work Unit created successfully');
    }

    public function render()
    {
        $companies = Company::orderBy('name')->get();

        return view('livewire.pages.work-units.create', [
            'companies' => $companies,
        ]);
    }
}
