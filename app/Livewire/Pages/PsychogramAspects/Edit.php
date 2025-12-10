<?php

namespace App\Livewire\Pages\PsychogramAspects;

use App\Models\PsychogramAspect;
use App\Models\Company;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Edit Psychogram Aspect')]
class Edit extends Component
{
    public PsychogramAspect $aspect;

    #[Validate('nullable|exists:companies,id')]
    public $company_id;

    #[Validate('required|string|max:20')]
    public $aspect_code;

    #[Validate('required|string|max:255')]
    public $aspect_name;

    #[Validate('nullable|string')]
    public $description;

    #[Validate('required|boolean')]
    public $is_active;

    #[Validate('required|integer|min:0')]
    public $sort_order;

    public function mount(PsychogramAspect $psychogram_aspect)
    {
        $this->aspect = $psychogram_aspect;
        $this->company_id = $psychogram_aspect->company_id;
        $this->aspect_code = $psychogram_aspect->aspect_code;
        $this->aspect_name = $psychogram_aspect->aspect_name;
        $this->description = $psychogram_aspect->description;
        $this->is_active = $psychogram_aspect->is_active;
        $this->sort_order = $psychogram_aspect->sort_order;
    }

    public function update()
    {
        $this->validate();

        try {
            // Jika company_id adalah string kosong, set menjadi null
            $companyId = $this->company_id === '' ? null : $this->company_id;

            // Validasi uniqueness berdasarkan company_id (kecuali untuk record ini sendiri)
            $exists = PsychogramAspect::where('company_id', $companyId)
                ->where('aspect_code', $this->aspect_code)
                ->where('id', '!=', $this->aspect->id)
                ->exists();

            if ($exists) {
                $this->addError('aspect_code', 'This aspect code already exists for the selected company.');
                return;
            }

            $this->aspect->update([
                'company_id' => $companyId,
                'aspect_code' => $this->aspect_code,
                'aspect_name' => $this->aspect_name,
                'description' => $this->description,
                'is_active' => $this->is_active,
                'sort_order' => $this->sort_order,
            ]);

            return redirect()->route('psychogram-aspects.index')->success('Psychogram aspect updated successfully');
        } catch (\Exception $e) {
            Toaster::error('Failed to update psychogram aspect: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('psychogram-aspects.index');
    }

    public function render()
    {
        $companies = Company::orderBy('name')->get();

        return view('livewire.pages.psychogram-aspects.edit', [
            'companies' => $companies,
        ]);
    }
}
