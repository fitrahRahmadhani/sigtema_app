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
    #[Validate('nullable|exists:companies,id')]
    public $company_id = '';

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

    public $selectedCompanyId = null;
    public $selectedCompany = null;
    public $companies;
    public $isSuperadmin = false;
    public $showCompanySelection = false;

    public function mount()
    {
        $user = Auth::user();
        $this->isSuperadmin = $user->hasRole('superadmin');

        if ($this->isSuperadmin) {
            $this->companies = Company::where('is_active', true)->get();
            $this->selectedCompanyId = session('psychogram_aspects_company_id');

            if ($this->selectedCompanyId) {
                $this->selectedCompany = Company::find($this->selectedCompanyId);
                $this->company_id = $this->selectedCompanyId;
            } else {
                $this->showCompanySelection = true;
            }
        } else {
            // Admin biasa hanya bisa akses perusahaan mereka sendiri
            $this->selectedCompanyId = $user->company_id;
            $this->selectedCompany = Company::find($this->selectedCompanyId);
            $this->company_id = $this->selectedCompanyId;
            $this->companies = Company::where('id', $user->company_id)->get();
        }
    }

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

            // Untuk admin biasa, pastikan mereka hanya bisa membuat aspect untuk company mereka sendiri
            if (!$this->isSuperadmin && $companyId != $this->selectedCompanyId) {
                Toaster::error('You are not authorized to create aspects for other companies.');
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
        // Jika superadmin belum pilih company
        if ($this->showCompanySelection) {
            // Hitung jumlah data aspect per perusahaan
            $companiesWithCount = $this->companies->map(function ($company) {
                $count = PsychogramAspect::where('company_id', $company->id)->count();

                return [
                    'company' => $company,
                    'count' => $count
                ];
            });

            return view('livewire.pages.psychogram-aspects.create', [
                'showCompanySelection' => true,
                'companiesWithCount' => $companiesWithCount,
                'isSuperadmin' => $this->isSuperadmin,
                'selectedCompany' => null,
            ]);
        }

        return view('livewire.pages.psychogram-aspects.create', [
            'showCompanySelection' => false,
            'selectedCompany' => $this->selectedCompany,
            'isSuperadmin' => $this->isSuperadmin,
            'companies' => $this->companies,
        ]);
    }
}
