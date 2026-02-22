<?php

namespace App\Livewire\Pages\PsychogramResults;

use App\Livewire\Traits\HandleException;
use App\Livewire\Traits\UnauthorizedAccess;
use App\Models\PsychogramResult;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Edit Psychogram Result')]
class Edit extends Component
{
    use UnauthorizedAccess, HandleException;

    public PsychogramResult $result;

    public $score;

    public function mount(PsychogramResult $psychogram_result)
    {
        $this->result = $psychogram_result;
        $this->score = $psychogram_result->score;
    }

    public function rules()
    {
        return [
            'score' => 'required|integer|min:1|max:5',
        ];
    }

    public function messages()
    {
        return [
            'score.required' => 'Score is required.',
            'score.integer'  => 'Score must be a number.',
            'score.min'      => 'Score must be at least 1.',
            'score.max'      => 'Score must not exceed 5.',
        ];
    }

    public function update()
    {
        try {
            $this->validate();
            $this->authorize('update', $this->result);

            DB::beginTransaction();

            $this->result->update([
                'score' => $this->score,
            ]);

            DB::commit();

            return redirect()->route('psychogram-results.index')
                ->success('Psychogram result updated successfully');
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return $this->unauthorizedAccess('edit psychogram result');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException('edit psychogram result', $e->getMessage());
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
