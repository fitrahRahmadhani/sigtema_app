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
#[Title('Add Technical Competency')]
class Create extends Component
{
    public $employee_id;
    public $name;
    public $issued_by;
    public $year;

    public $company_id = '';
    public $selectedCompanyId = null;
    public $selectedCompany = null;
    public $companies = [];
    public $employees = [];
    public $isSuperadmin = false;

    public function mount()
    {
        $user = Auth::user();
        $this->isSuperadmin = $user->hasRole('superadmin');

        if ($this->isSuperadmin) {
            $this->companies = Company::where('is_active', true)->get();
            $this->selectedCompanyId = session('technical_competencies_company_id');

            if ($this->selectedCompanyId) {
                $this->selectedCompany = Company::find($this->selectedCompanyId);
                $this->company_id = $this->selectedCompanyId;
                $this->loadEmployees();
            }
        } else {
            // Admin biasa hanya bisa akses perusahaan mereka sendiri
            $this->selectedCompanyId = $user->company_id;
            $this->selectedCompany = Company::find($this->selectedCompanyId);
            $this->company_id = $this->selectedCompanyId;
            $this->loadEmployees();
        }

        $this->year = date('Y');
    }

    private function loadEmployees()
    {
        if ($this->selectedCompanyId) {
            $this->employees = Employee::where('company_id', $this->selectedCompanyId)
                ->orderBy('name')
                ->get();
        } else {
            $this->employees = collect();
        }
    }

    public function updatedCompanyId($companyId)
    {
        if ($companyId && $this->isSuperadmin) {
            $this->employees = Employee::where('company_id', $companyId)
                ->orderBy('name')
                ->get();
        } else {
            $this->employees = collect();
        }
        $this->employee_id = null;
    }

    public function rules()
    {
        return [
            'company_id' => 'required|exists:companies,id',
            'employee_id' => 'required|exists:employees,id',
            'name' => 'required|string|max:255',
            'issued_by' => 'required|string|max:255',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ];
    }

    public function messages()
    {
        return [
            'company_id.required' => 'Please select a company.',
            'employee_id.required' => 'Please select an employee.',
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

            TechnicalCompetency::create([
                'employee_id' => $this->employee_id,
                'name' => $this->name,
                'issued_by' => $this->issued_by,
                'year' => $this->year,
            ]);

            return redirect()->route('technical-competencies.index', ['company_id' => $this->selectedCompanyId])
                ->success('Technical competency has been added successfully.');
        } catch (Exception $e) {
            Toaster::error('Failed to add technical competency. Please try again.');
        }
    }

    public function cancel()
    {
        return redirect()->route('technical-competencies.index', ['company_id' => $this->selectedCompanyId]);
    }

    public function render()
    {
        return view('livewire.pages.technical-competencies.create');
    }
}
