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
#[Title('Create Psychogram Aspect')]
class Create extends Component
{
    #[Validate('nullable|exists:companies,id')]
    public $company_id = '';

    #[Validate('required|string|max:20|unique:psychogram_aspects,aspect_code')]
    public $aspect_code = '';

    #[Validate('required|string|max:255')]
    public $aspect_name = '';

    #[Validate('nullable|string')]
    public $description = '';

    #[Validate('required|boolean')]
    public $is_active = true;

    #[Validate('required|integer|min:0')]
    public $sort_order = 0;

    public function save()
    {
        $this->validate();

        try {
            // Jika company_id adalah string kosong, set menjadi null
            $companyId = $this->company_id === '' ? null : $this->company_id;

            // Validasi uniqueness berdasarkan company_id
            $exists = PsychogramAspect::where('company_id', $companyId)
                ->where('aspect_code', $this->aspect_code)
                ->exists();

            if ($exists) {
                $this->addError('aspect_code', 'This aspect code already exists for the selected company.');
                return;
            }

            PsychogramAspect::create([
                'company_id' => $companyId,
                'aspect_code' => $this->aspect_code,
                'aspect_name' => $this->aspect_name,
                'description' => $this->description,
                'is_active' => $this->is_active,
                'sort_order' => $this->sort_order,
            ]);

            return redirect()->route('psychogram-aspects.index')->success('Psychogram aspect created successfully');
        } catch (\Exception $e) {
            Toaster::error('Failed to create psychogram aspect: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('psychogram-aspects.index');
    }

    public function render()
    {
        $companies = Company::orderBy('name')->get();

        return view('livewire.pages.psychogram-aspects.create', [
            'companies' => $companies,
        ]);
    }
}
