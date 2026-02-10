<?php

namespace App\Livewire\Pages\TechnicalCompetencies;

use App\Models\TechnicalCompetency;
use App\Models\Employee;
use App\Models\Company;
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
    public TechnicalCompetency $technicalCompetency;

    public $employee_id;
    public $name;
    public $issued_by;
    public $year;

    public $selectedCompany = null;
    public $selectedEmployee = null;
    public $isSuperadmin = false;

    public function mount(TechnicalCompetency $technical_competency)
    {
        $this->technicalCompetency = $technical_competency;

        // Load data ke form
        $this->employee_id = $technical_competency->employee_id;
        $this->name = $technical_competency->name;
        $this->issued_by = $technical_competency->issued_by;
        $this->year = $technical_competency->year;

        $user = Auth::user();
        $this->isSuperadmin = $user->hasRole('superadmin');

        // Load employee dan company (read-only)
        $this->selectedEmployee = Employee::find($this->employee_id);
        $this->selectedCompany = $this->selectedEmployee->company;

        // Validasi akses
        if (!$this->isSuperadmin && $this->selectedEmployee->company_id !== $user->company_id) {
            abort(403, 'Unauthorized access to this technical competency.');
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

    public function save()
    {
        try {
            $this->validate();

            $this->technicalCompetency->update([
                'name' => $this->name,
                'issued_by' => $this->issued_by,
                'year' => $this->year,
            ]);

            return redirect()->route('technical-competencies.index')
                ->success('Technical competency has been updated successfully.');
        } catch (Exception $e) {
            Toaster::error('Failed to update technical competency. Please try again.');
        }
    }

    public function cancel()
    {
        return redirect()->route('technical-competencies.index');
    }

    public function render()
    {
        return view('livewire.pages.technical-competencies.edit');
    }
}
