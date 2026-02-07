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
#[Title('Edit Position History')]
class Edit extends Component
{
    public PositionHistory $positionHistory;

    public $position_name;
    public $work_unit;
    public $start_date;
    public $end_date;
    public $company;

    public $selectedCompany = null;
    public $selectedEmployee = null;
    public $isSuperadmin = false;

    public function mount(PositionHistory $position_history)
    {
        $this->positionHistory = $position_history;

        // Load data ke form
        $this->position_name = $position_history->position_name;
        $this->work_unit = $position_history->work_unit;
        $this->start_date = $position_history->start_date?->format('Y-m-d');
        $this->end_date = $position_history->end_date?->format('Y-m-d');
        $this->company = $position_history->company;

        $user = Auth::user();
        $this->isSuperadmin = $user->hasRole('superadmin');

        // Load employee dan company (read-only)
        $this->selectedEmployee = Employee::find($position_history->employee_id);
        $this->selectedCompany = Company::find($this->selectedEmployee->company_id);

        // Validasi akses
        if (!$this->isSuperadmin && $this->selectedEmployee->company_id !== $user->company_id) {
            abort(403, 'Unauthorized access to this position history.');
        }
    }

    public function rules()
    {
        return [
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

            $this->positionHistory->update([
                'position_name' => $this->position_name,
                'work_unit' => $this->work_unit,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'company' => $this->company,
            ]);

            return redirect()->route('position-histories.index')
                ->success('Position history has been updated successfully.');
        } catch (Exception $e) {
            Toaster::error('Failed to update position history. Please try again.');
        }
    }

    public function cancel()
    {
        return redirect()->route('position-histories.index');
    }

    public function render()
    {
        return view('livewire.pages.position-histories.edit');
    }
}
