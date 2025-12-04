<?php

namespace App\Livewire\Pages\PositionHistories;

use App\Models\PositionHistory;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
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

    public function mount(PositionHistory $position_history)
    {
        $this->history = $position_history;

        // Set nilai dari history yang akan diedit
        $this->position_name = $position_history->position_name;
        $this->work_unit = $position_history->work_unit;
        $this->start_date = $position_history->start_date?->format('Y-m-d');
        $this->end_date = $position_history->end_date?->format('Y-m-d');
        $this->company = $position_history->company;
    }

    public function rules()
    {
        return [
            'position_name' => 'required|string|max:255',
            'work_unit' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'company' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'position_name.required' => 'Position name is required.',
            'work_unit.required' => 'Work unit is required.',
            'start_date.required' => 'Start date is required.',
            'end_date.required' => 'End date is required.',
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

            return redirect()->route('position-histories.index')->success('Position history has been updated successfully.');
        } catch (Exception $e) {
            Toaster::error('Failed to update position history. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.pages.position-histories.edit', [
            'history' => $this->history,
        ]);
    }
}
