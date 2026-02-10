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
#[Title('Create Psychogram Aspect')]
class Create extends Component
{
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

    public $company_id = '';
    public $selectedCompany = null;
    public $isSuperadmin = false;

    public function mount()
    {
        $user = Auth::user();
        $this->isSuperadmin = $user->hasRole('superadmin');

        // Ambil company_id dari session untuk superadmin, atau dari user untuk admin biasa
        if ($this->isSuperadmin) {
            $companyId = session('psychogram_aspects_company_id');
            if (!$companyId) {
                // Redirect ke index jika belum ada company di session
                return redirect()->route('psychogram-aspects.index');
            }
        } else {
            $companyId = $user->company_id;
        }

        $this->selectedCompany = Company::find($companyId);
        $this->company_id = $companyId;
    }

    public function save()
    {
        $this->validate();

        try {
            // Validasi uniqueness berdasarkan company_id
            $exists = PsychogramAspect::where('company_id', $this->company_id)
                ->where('aspect_code', $this->aspect_code)
                ->exists();

            if ($exists) {
                $this->addError('aspect_code', 'This aspect code already exists for this company.');
                return;
            }

            PsychogramAspect::create([
                'company_id' => $this->company_id,
                'aspect_code' => $this->aspect_code,
                'aspect_name' => $this->aspect_name,
                'description' => $this->description,
                'is_active' => $this->is_active,
                'sort_order' => $this->sort_order,
            ]);

            return redirect()->route('psychogram-aspects.index')
                ->success('Psychogram aspect created successfully');
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
        return view('livewire.pages.psychogram-aspects.create');
    }
}
