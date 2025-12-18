<?php

namespace App\Livewire\Pages\PositionHistories;

use App\Models\PositionHistory;
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
    public PositionHistory $history;
    public $position_name;
    public $work_unit;
    public $start_date;
    public $end_date;
    public $company;

    // Tambahkan property untuk superadmin
    public $selectedCompanyId = null;
    public $selectedCompany = null;
    public $isSuperadmin = false;

    public function mount(PositionHistory $position_history)
    {
        $this->history = $position_history;

        // Set nilai dari history yang akan diedit
        $this->position_name = $position_history->position_name;
        $this->work_unit = $position_history->work_unit;
        $this->start_date = $position_history->start_date?->format('Y-m-d');
        $this->end_date = $position_history->end_date?->format('Y-m-d');
        $this->company = $position_history->company;

        // Cek role user
        $user = Auth::user();
        $this->isSuperadmin = $user->hasRole('superadmin');

        if ($this->history->employee && $this->history->employee->company) {
            $this->selectedCompanyId = $this->history->employee->company_id;
            $this->selectedCompany = $this->history->employee->company;
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

    public function update()
    {
        try {
            $this->validate();

            $this->history->update([
                'position_name' => $this->position_name,
                'work_unit' => $this->work_unit,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'company' => $this->company,
            ]);

            return redirect()->route('position-histories.index', ['company_id' => $this->selectedCompanyId])
                ->success('Position history has been updated successfully.');
        } catch (Exception $e) {
            Toaster::error('Failed to update position history. Please try again.');
        }
    }

    public function cancel()
    {
        return redirect()->route('position-histories.index', ['company_id' => $this->selectedCompanyId]);
    }

    public function render()
    {
        return view('livewire.pages.position-histories.edit');
    }
}
