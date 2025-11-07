<?php

namespace App\Livewire\Pages\Companies;

use App\Models\Company;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

#[Layout('components.layouts.app')]
class Create extends Component
{
    use WithFileUploads;

    #[Title('Create Company')]

    #[Validate('required|image|max:2048')]
    public $logo;

    #[Validate('required|string|max:255')]
    public $name;

    #[Validate('required|string|max:255')]
    public $cocd;

    #[Validate('boolean')]
    public $is_active = true;

    public function save()
    {
        $this->validate();

        // Generate custom path: company-name_YYYYMMDD.extension
        $date = now()->format('Ymd');
        $slug = Str::uuid();
        $extension = $this->logo->getClientOriginalExtension();
        $filename = "{$slug}_{$date}.{$extension}";

        // Store with custom filename
        $logoPath = $this->logo->storeAs('companies/logos', $filename, 'public');

        Company::create([
            'slug' => $slug,
            'name' => $this->name,
            'cocd' => $this->cocd,
            'logo_path' => $logoPath,
            'is_active' => $this->is_active,
        ]);


        return redirect()->route('companies.index')->success('Company created successfully.');
    }

    public function render()
    {
        return view('livewire.pages.companies.create');
    }
}
