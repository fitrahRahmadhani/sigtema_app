<?php

namespace App\Livewire\Pages\Mcus;

use App\Models\Mcu;
use App\Models\Company;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
#[Title('Edit MCU Record')]
class Edit extends Component
{
    public Mcu $mcu;
    public $result;
    public $year;
    public $selectedCompany = null;

    public function mount(Mcu $mcu)
    {
        $this->mcu = $mcu;
        $this->result = $mcu->result;
        $this->year = $mcu->year;
        $this->selectedCompany = $mcu->employee->company;
    }

    public function rules()
    {
        return [
            'result' => 'required|in:K1,K2,K3,K4,K5',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ];
    }

    public function update()
    {
        $this->validate();

        $this->mcu->update([
            'result' => $this->result,
            'year' => $this->year,
        ]);

        return redirect()->route('mcus.index')->success('MCU record has been updated successfully.');
    }

    public function cancel()
    {
        return redirect()->route('mcus.index');
    }

    public function render()
    {
        return view('livewire.pages.mcus.edit');
    }
}
