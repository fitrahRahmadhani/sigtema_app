<?php

namespace App\Livewire\Pages\PositionHistories;

use App\Models\PositionHistory;
use App\Models\Employee;
use App\Models\Company;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Masmerise\Toaster\Toaster;
use Exception;

#[Layout('components.layouts.app')]
#[Title('Add Position History')]
class Create extends Component
{
    public $employee_id;
    public $position_name;
    public $work_unit;
    public $start_date;
    public $end_date;
    public $company;

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
            $this->selectedCompanyId = session('position_histories_company_id');

            if ($this->selectedCompanyId) {
                $this->selectedCompany = Company::find($this->selectedCompanyId);
                $this->company_id = $this->selectedCompanyId;
                $this->loadEmployees();
                // Auto-fill company name from selected company
                if ($this->selectedCompany) {
                    $this->company = $this->selectedCompany->name;
                }
            }
        } else {
            // Admin biasa hanya bisa akses perusahaan mereka sendiri
            $this->selectedCompanyId = $user->company_id;
            $this->selectedCompany = Company::find($this->selectedCompanyId);
            $this->company_id = $this->selectedCompanyId;
            $this->loadEmployees();
            // Auto-fill company name from user's company
            if ($this->selectedCompany) {
                $this->company = $this->selectedCompany->name;
            }
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
            // Update company name when company changes
            $selectedCompany = Company::find($companyId);
            if ($selectedCompany) {
                $this->company = $selectedCompany->name;
            }
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
            'position_name' => 'required|string|max:255',
            'work_unit' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'company' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'company_id.required' => 'Please select a company.',
            'employee_id.required' => 'Please select an employee.',
            'position_name.required' => 'Position name is required.',
            'work_unit.required' => 'Work unit is required.',
            'start_date.required' => 'Start date is required.',
            'end_date.after_or_equal' => 'End date must be after or equal to start date.',
            'company.required' => 'Company name is required.',
        ];
    }

    public function save()
    {
        try {
            $this->validate();

            PositionHistory::create([
                'employee_id' => $this->employee_id,
                'position_name' => $this->position_name,
                'work_unit' => $this->work_unit,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'company' => $this->company,
            ]);

            return redirect()->route('position-histories.index', ['company_id' => $this->selectedCompanyId])
                ->success('Position history has been added successfully.');
        } catch (Exception $e) {
            Toaster::error('Failed to add position history. Please try again.');
        }
    }

    public function cancel()
    {
        return redirect()->route('position-histories.index', ['company_id' => $this->selectedCompanyId]);
    }

    public function render()
    {
        return view('livewire.pages.position-histories.create');
    }
}
