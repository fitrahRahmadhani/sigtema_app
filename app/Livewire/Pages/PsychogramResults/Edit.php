<?php

namespace App\Livewire\Pages\PsychogramResults;

use App\Models\PsychogramResult;
use App\Models\Employee;
use App\Models\Company;
use App\Models\PsychogramAspect;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Edit Psychogram Result')]
class Edit extends Component
{
    public PsychogramResult $result;

    #[Validate('required|integer|min:1|max:5')]
    public $score;

    public function mount(PsychogramResult $psychogram_result)
    {
        $this->result = $psychogram_result;
        $this->score = $psychogram_result->score;
    }

    public function update()
    {
        $this->validate();

        try {
            $this->result->update([
                'score' => $this->score,
            ]);

            return redirect()->route('psychogram-results.index')->success('Psychogram result updated successfully');
        } catch (\Exception $e) {
            Toaster::error('Failed to update psychogram result: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('psychogram-results.index');
    }

    public function render()
    {
        return view('livewire.pages.psychogram-results.edit', [
            'result' => $this->result,
        ]);
    }
}
