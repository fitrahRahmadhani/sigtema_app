<?php

namespace App\Livewire\Pages\Companies;

use App\Models\Company;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.app')]
#[Title('Edit Company')]
class Edit extends Component
{
    use WithFileUploads;

    public Company $company;

    #[Validate('nullable|image|max:2048')]
    public $logo;

    #[Validate('required|string|max:255')]
    public $name;

    #[Validate('required|string|max:255')]
    public $cocd;

    #[Validate('boolean')]
    public $is_active;

    public $current_logo;

    public function mount(Company $company)
    {
        $this->company = $company;
        $this->name = $company->name;
        $this->cocd = $company->cocd;
        $this->is_active = $company->is_active;
        $this->current_logo = $company->logo_path;
    }

    public function save()
    {
        // Validasi dengan unique kecuali company ini sendiri
        $this->validate([
            'logo' => 'nullable|image|max:2048',
            'name' => 'required|string|max:255',
            'cocd' => 'required|string|max:255|unique:companies,cocd,' . $this->company->id,
            'is_active' => 'boolean',
        ]);

        $data = [
            'slug' => Str::slug($this->name),
            'name' => $this->name,
            'cocd' => $this->cocd,
            'is_active' => $this->is_active,
        ];

        // Jika ada logo baru yang diupload
        if ($this->logo) {
            // Hapus logo lama jika ada
            if ($this->company->logo_path && Storage::disk('public')->exists($this->company->logo_path)) {
                Storage::disk('public')->delete($this->company->logo_path);
            }

            // Upload logo baru
            $date = now()->format('Ymd');
            $slug = Str::slug($this->name);
            $extension = $this->logo->getClientOriginalExtension();
            $filename = "{$slug}_{$date}.{$extension}";

            $logoPath = $this->logo->storeAs('companies/logos', $filename, 'public');
            $data['logo_path'] = $logoPath;
        }

        $this->company->update($data);

        session()->flash('success', 'Company updated successfully!');

        return $this->redirect(route('companies.index'), navigate: true);
    }

    public function removeLogo()
    {
        if ($this->company->logo_path && Storage::disk('public')->exists($this->company->logo_path)) {
            Storage::disk('public')->delete($this->company->logo_path);
        }

        $this->company->update(['logo_path' => null]);
        $this->current_logo = null;
        $this->logo = null;

        session()->flash('success', 'Logo removed successfully!');
    }

    public function render()
    {
        return view('livewire.pages.companies.edit');
    }
}
