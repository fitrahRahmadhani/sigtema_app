<?php

namespace App\Livewire\Pages\Educations;

use App\Models\Education;
use App\Models\Employee;
use App\Models\Company;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Masmerise\Toaster\Toaster;
use Exception;

#[Layout('components.layouts.app')]
#[Title('Add Education')]
class Create extends Component
{
    public $employee_id;
    public $institution_name;
    public $major;
    public $degree;
    public $start_date;
    public $end_date;

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
            $this->selectedCompanyId = session('educations_company_id');

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

    public function updatedIsStudying($value)
    {
        if ($value) {
            $this->end_date = null;
        }
    }

    public function rules()
    {
        return [
            'company_id' => 'required|exists:companies,id',
            'employee_id' => 'required|exists:employees,id',
            'institution_name' => 'required|string|max:255',
            'major' => 'required|string|max:255',
            'degree' => 'required|string|max:100',
            'start_date' => 'required|date|before_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];
    }

    public function messages()
    {
        return [
            'company_id.required' => 'Please select a company.',
            'employee_id.required' => 'Please select an employee.',
            'institution_name.required' => 'Institution name is required.',
            'major.required' => 'Major/Field of study is required.',
            'degree.required' => 'Degree level is required.',
            'start_date.required' => 'Start date is required.',
            'start_date.before_or_equal' => 'Start date cannot be in the future.',
            'end_date.after_or_equal' => 'End date must be after or equal to start date.',
        ];
    }

    public function save()
    {
        try {
            $this->validate();

            Education::create([
                'employee_id' => $this->employee_id,
                'institution_name' => $this->institution_name,
                'major' => $this->major,
                'degree' => $this->degree,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
            ]);

            return redirect()->route('educations.index')->success('Education record has been added successfully.');
        } catch (Exception $e) {
            Toaster::error('Failed to add education record. Please try again.');
        }
    }

    public function cancel()
    {
        return redirect()->route('educations.index');
    }

    public function render()
    {
        return view('livewire.pages.educations.create');
    }
}
