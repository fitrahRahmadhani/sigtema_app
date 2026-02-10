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
    public PsychogramAspect $psychogramAspect;

    #[Validate('required|string|max:20')]
    public $aspect_code = '';

    #[Validate('required|string|max:255')]
    public $aspect_name = '';

    #[Validate('nullable|string')]
    public $description = '';

    #[Validate('required|boolean')]
    public $is_active = true;

    #[Validate('required|integer|min:0')]
    public $sort_order = 0;

    public $selectedCompany = null;
    public $isSuperadmin = false;

    public function mount(PsychogramAspect $psychogram_aspect)
    {
        $this->psychogramAspect = $psychogram_aspect;

        // Load data ke form
        $this->aspect_code = $psychogram_aspect->aspect_code;
        $this->aspect_name = $psychogram_aspect->aspect_name;
        $this->description = $psychogram_aspect->description;
        $this->is_active = $psychogram_aspect->is_active;
        $this->sort_order = $psychogram_aspect->sort_order;

        $user = Auth::user();
        $this->isSuperadmin = $user->hasRole('superadmin');

        // Load company (read-only)
        $this->selectedCompany = Company::find($psychogram_aspect->company_id);

        // Validasi akses
        if (!$this->isSuperadmin && $psychogram_aspect->company_id !== $user->company_id) {
            abort(403, 'Unauthorized access to this psychogram aspect.');
        }
    }

    public function save()
    {
        $this->validate();

        try {
            // Validasi uniqueness berdasarkan company_id (exclude current record)
            $exists = PsychogramAspect::where('company_id', $this->psychogramAspect->company_id)
                ->where('aspect_code', $this->aspect_code)
                ->where('id', '!=', $this->psychogramAspect->id)
                ->exists();

            if ($exists) {
                $this->addError('aspect_code', 'This aspect code already exists for this company.');
                return;
            }

            $this->psychogramAspect->update([
                'aspect_code' => $this->aspect_code,
                'aspect_name' => $this->aspect_name,
                'description' => $this->description,
                'is_active' => $this->is_active,
                'sort_order' => $this->sort_order,
            ]);

            return redirect()->route('psychogram-aspects.index')
                ->success('Psychogram aspect updated successfully');
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
        return view('livewire.pages.psychogram-aspects.edit');
    }
}
