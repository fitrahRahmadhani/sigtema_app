<?php

namespace App\Livewire\Pages\Mcus;

use App\Models\Mcu;
use App\Models\Employee;
use App\Models\Company;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Masmerise\Toaster\Toaster;
use Exception;

#[Layout('components.layouts.app')]
#[Title('Add MCU Record')]
class Create extends Component
{
    public $employee_id;
    public $result;
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
            $this->selectedCompanyId = session('mcus_company_id');

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
            'result' => 'required|in:K1,K2,K3,K4,K5',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ];
    }

    public function messages()
    {
        return [
            'company_id.required' => 'Please select a company.',
            'employee_id.required' => 'Please select an employee.',
            'result.required' => 'MCU result is required.',
            'result.in' => 'Please select a valid MCU result (K1-K5).',
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

            Mcu::create([
                'employee_id' => $this->employee_id,
                'result' => $this->result,
                'year' => $this->year,
            ]);

            return redirect()->route('mcus.index')->success('MCU record has been added successfully.');
        } catch (Exception $e) {
            Toaster::error('Failed to add MCU record. Please try again.');
        }
    }

    public function cancel()
    {
        return redirect()->route('mcus.index');
    }

    public function render()
    {
        return view('livewire.pages.mcus.create');
    }
}
