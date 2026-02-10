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
use Illuminate\Support\Facades\Auth;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Edit Psychogram Result')]
class Edit extends Component
{
    public PsychogramResult $result;

    #[Validate('required|integer|min:1|max:5')]
    public $score;

    public $isSuperadmin = false;

    public function mount(PsychogramResult $psychogram_result)
    {
        $user = Auth::user();
        $this->isSuperadmin = $user->hasRole('superadmin');

        // Check authorization
        if (!$this->isSuperadmin) {
            // Admin biasa hanya bisa edit data dari company mereka sendiri
            if ($psychogram_result->employee->company_id !== $user->company_id) {
                abort(403, 'Unauthorized access');
            }
        }

        $this->result = $psychogram_result;
        $this->score = $psychogram_result->score;
    }

    public function update()
    {
        $this->validate();

        try {
            // Check if score actually changed
            if ($this->result->score == $this->score) {
                Toaster::warning('No changes were made.');
                return redirect()->route('psychogram-results.index');
            }

            $this->result->update([
                'score' => $this->score,
            ]);

            Toaster::success('Psychogram result updated successfully.');
            return redirect()->route('psychogram-results.index');
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
        return view('livewire.pages.psychogram-results.edit');
    }
}
