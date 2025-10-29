<?php

namespace App\Livewire\Pages\Companies;

use App\Models\Company;
use Livewire\Component;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
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

    #[Validate('required|numeric')]
    public $cocd;

    public function save()
    {
        $this->validate();

        $logoPath = $this->logo->store('companies/logos', 'public');

        Company::create([
            'logo' => $logoPath,
            'name' => $this->name,
            'cocd' => $this->cocd,
        ]);

        Toaster::success('Company created successfully');

        return $this->redirect(route('companies.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.pages.companies.create');
    }
}
