<?php

namespace App\Livewire\Pages\Departments;

use App\Models\Departemen;
use App\Models\Company;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Edit Department')]
class Edit extends Component
{
    public Departemen $department;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|exists:companies,id')]
    public $company_id = '';

    public function mount(Departemen $department)
    {
        $this->department = $department;
        $this->name = $department->name;
        $this->company_id = $department->company_id;
    }

    public function save()
    {
        $this->validate();

        try {
            $this->department->update([
                'name' => $this->name,
                'company_id' => $this->company_id,
            ]);

            Toaster::success('Department updated successfully!');

            return redirect()->route('departments.index')->success('Department updated successfully');
        } catch (\Exception $e) {
            Toaster::error('Failed to update department: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('departments.index');
    }

    public function render()
    {
        $companies = Company::orderBy('name')->get();

        return view('livewire.pages.departments.edit', [
            'companies' => $companies,
        ]);
    }
}
