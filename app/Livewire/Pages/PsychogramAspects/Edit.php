<?php

namespace App\Livewire\Pages\PsychogramAspects;

use App\Models\PsychogramAspect;
use App\Models\Company;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
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

    public $selectedCompanyId = null;
    public $selectedCompany = null;
    public $companies = [];
    public $isSuperadmin = false;

    public function mount(PsychogramAspect $psychogram_aspect)
    {
        $user = Auth::user();
        $this->isSuperadmin = $user->hasRole('superadmin');

        // Check authorization
        if (!$this->isSuperadmin) {
            // Admin biasa hanya bisa edit aspect dari company mereka sendiri atau global aspects
            if ($psychogram_aspect->company_id && $psychogram_aspect->company_id !== $user->company_id) {
                abort(403, 'Unauthorized access');
            }
        }

        $this->aspect = $psychogram_aspect;
        $this->company_id = $psychogram_aspect->company_id;
        $this->aspect_code = $psychogram_aspect->aspect_code;
        $this->aspect_name = $psychogram_aspect->aspect_name;
        $this->description = $psychogram_aspect->description;
        $this->is_active = $psychogram_aspect->is_active;
        $this->sort_order = $psychogram_aspect->sort_order;

        // Set companies list based on user role
        if ($this->isSuperadmin) {
            $this->companies = Company::where('is_active', true)->get();
        } else {
            $this->companies = Company::where('id', $user->company_id)->get();
        }

        // Set selected company for display
        if ($psychogram_aspect->company) {
            $this->selectedCompanyId = $psychogram_aspect->company_id;
            $this->selectedCompany = $psychogram_aspect->company;
        }
    }

    public function update()
    {
        $this->validate();

        try {
            // Jika company_id adalah string kosong, set menjadi null
            $companyId = $this->company_id === '' ? null : $this->company_id;

            // Untuk admin biasa, pastikan mereka hanya bisa mengubah aspect untuk company mereka sendiri
            if (!$this->isSuperadmin) {
                // Admin biasa tidak bisa mengubah company assignment
                if ($companyId != $this->aspect->company_id) {
                    Toaster::error('You are not authorized to change company assignment.');
                    return;
                }

                // Pastikan mereka hanya bisa mengedit global aspects atau aspects dari company mereka
                if ($this->aspect->company_id && $this->aspect->company_id !== Auth::user()->company_id) {
                    Toaster::error('You are not authorized to edit aspects from other companies.');
                    return;
                }
            }

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
        return view('livewire.pages.psychogram-aspects.edit', [
            'companies' => $this->companies,
            'selectedCompany' => $this->selectedCompany,
            'isSuperadmin' => $this->isSuperadmin,
        ]);
    }
}
