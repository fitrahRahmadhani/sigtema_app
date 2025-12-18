<?php

namespace App\Livewire\Pages\TechnicalCompetencies;

use App\Models\TechnicalCompetency;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Masmerise\Toaster\Toaster;
use Exception;

#[Layout('components.layouts.app')]
#[Title('Edit Technical Competency')]
class Edit extends Component
{
    public TechnicalCompetency $competency;
    public $name;
    public $issued_by;
    public $year;

    // Tambahkan property untuk superadmin
    public $selectedCompanyId = null;
    public $selectedCompany = null;
    public $isSuperadmin = false;

    public function mount(TechnicalCompetency $technical_competency)
    {
        $this->competency = $technical_competency;

        // Set nilai dari competency yang akan diedit
        $this->name = $technical_competency->name;
        $this->issued_by = $technical_competency->issued_by;
        $this->year = $technical_competency->year;

        // Cek role user
        $user = Auth::user();
        $this->isSuperadmin = $user->hasRole('superadmin');

        if ($this->competency->employee && $this->competency->employee->company) {
            $this->selectedCompanyId = $this->competency->employee->company_id;
            $this->selectedCompany = $this->competency->employee->company;
        }
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'issued_by' => 'required|string|max:255',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Competency name is required.',
            'issued_by.required' => 'Issued by is required.',
            'year.required' => 'Year is required.',
            'year.integer' => 'Year must be a valid number.',
            'year.min' => 'Year must be at least 2000.',
            'year.max' => 'Year cannot be more than ' . (date('Y') + 1) . '.',
        ];
    }

    public function update()
    {
        try {
            $this->validate();

            $this->competency->update([
                'name' => $this->name,
                'issued_by' => $this->issued_by,
                'year' => $this->year,
            ]);

            return redirect()->route('technical-competencies.index', ['company_id' => $this->selectedCompanyId])
                ->success('Technical competency has been updated successfully.');
        } catch (Exception $e) {
            Toaster::error('Failed to update technical competency. Please try again.');
        }
    }

    public function cancel()
    {
        return redirect()->route('technical-competencies.index', ['company_id' => $this->selectedCompanyId]);
    }

    public function render()
    {
        return view('livewire.pages.technical-competencies.edit');
    }
}