<?php

namespace App\Livewire\Pages\Departments;

use App\Models\Departemen;
use App\Models\Company;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Illuminate\Support\Str;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Create Department')]
class Create extends Component
{
    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|exists:companies,id')]
    public $company_id = '';

    public $slug = '';

    public function updatedName()
    {
        $this->slug = Str::slug($this->name);
    }

    public function save()
    {
        $this->validate();

        try {
            // Generate unique slug
            $slug = Str::slug($this->name);
            $originalSlug = $slug;
            $count = 1;

            while (Departemen::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }

            Departemen::create([
                'name' => $this->name,
                'company_id' => $this->company_id,
                'slug' => $slug,
            ]);

            Toaster::success('Department created successfully!');

            return $this->redirect(route('departments.index'), navigate: true);
        } catch (\Exception $e) {
            Toaster::error('Failed to create department: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return $this->redirect(route('departments.index'), navigate: true);
    }

    public function render()
    {
        $companies = Company::orderBy('name')->get();

        return view('livewire.pages.departments.create', [
            'companies' => $companies,
        ]);
    }
}
