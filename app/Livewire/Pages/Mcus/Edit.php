<?php

namespace App\Livewire\Pages\Mcus;

use App\Models\Mcu;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

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
            'result' => 'required|numeric|min:1|max:5',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ];
    }

    public function update()
    {
        try {
            $this->validate();
            $this->authorize('update', $this->mcu);
            DB::beginTransaction();
            $this->mcu->update([
                'result' => $this->result,
                'year' => $this->year,
            ]);
            DB::commit();
            return redirect()->route('mcus.index')->success('MCU record has been updated successfully.');
        } catch (ValidationException $e) {
            DB::rollBack();
            Toaster::error('Validation failed: ' . $e->getMessage());
            throw $e;
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return $this->unauthorizedAccess('edit mcu');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException('edit mcu', $e->getMessage());
        }
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
