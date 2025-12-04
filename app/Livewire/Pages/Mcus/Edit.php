<?php

namespace App\Livewire\Pages\Mcus;

use App\Models\Mcu;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Masmerise\Toaster\Toaster;
use Exception;

#[Layout('components.layouts.app')]
#[Title('Edit MCU Record')]
class Edit extends Component
{
    public Mcu $mcu;

    public $result;
    public $year;

    public function mount(Mcu $mcu)
    {
        $this->mcu = $mcu;

        // Set nilai dari mcu yang akan diedit
        $this->result = $mcu->result;
        $this->year = $mcu->year;
    }

    public function rules()
    {
        return [
            'result' => 'required|in:K1,K2,K3,K4,K5',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ];
    }

    public function messages()
    {
        return [
            'result.required' => 'MCU result is required.',
            'result.in' => 'Please select a valid MCU result (K1-K5).',
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

            $this->mcu->update([
                'result' => $this->result,
                'year' => $this->year,
            ]);

            return redirect()->route('mcus.index')->success('MCU record has been updated successfully.');
        } catch (Exception $e) {
            Toaster::error('Failed to update MCU record. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.pages.mcus.edit', [
            'mcu' => $this->mcu,
        ]);
    }
}
