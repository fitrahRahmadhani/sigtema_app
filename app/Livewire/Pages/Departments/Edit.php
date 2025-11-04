<?php

namespace App\Livewire\Pages\Departments;

use App\Models\Departemen;
use App\Models\Company;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
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

    public $slug = '';

    public function mount(Departemen $department)
    {
        $this->department = $department;
        $this->name = $department->name;
        $this->company_id = $department->company_id;
        $this->slug = $department->slug;
    }

    public function updatedName()
    {
        $this->slug = Str::slug($this->name);
    }

    public function save()
    {
        $this->validate();

        try {
            // Generate unique slug if name changed
            $slug = Str::slug($this->name);

            // Check if slug needs to be unique (if changed)
            if ($slug !== $this->department->slug) {
                $originalSlug = $slug;
                $count = 1;

                while (Departemen::where('slug', $slug)->where('id', '!=', $this->department->id)->exists()) {
                    $slug = $originalSlug . '-' . $count;
                    $count++;
                }
            } else {
                $slug = $this->department->slug;
            }

            $this->department->update([
                'name' => $this->name,
                'company_id' => $this->company_id,
                'slug' => $slug,
            ]);

            Toaster::success('Department updated successfully!');

            return $this->redirect(route('departments.index'), navigate: true);
        } catch (\Exception $e) {
            Toaster::error('Failed to update department: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return $this->redirect(route('departments.index'), navigate: true);
    }

    public function render()
    {
        $companies = Company::orderBy('name')->get();

        return view('livewire.pages.departments.edit', [
            'companies' => $companies,
        ]);
    }
}
