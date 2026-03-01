<?php

namespace App\Livewire\Pages\PositionHistories;

use App\Livewire\Traits\HandleException;
use App\Livewire\Traits\UnauthorizedAccess;
use App\Models\Company;
use App\Models\Employee;
use App\Models\PositionHistory;
use App\Models\User;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Edit Position History')]
class Edit extends Component
{
    use UnauthorizedAccess, HandleException;

    public PositionHistory $positionHistory;

    public User $user;
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

        $this->user = Auth::user();
        $this->isSuperadmin = $this->user->hasRole('superadmin');

        // Load employee dan company (read-only)
        $this->selectedEmployee = Employee::find($position_history->employee_id);
        $this->selectedCompany = Company::find($this->selectedEmployee->company_id);
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
            $this->authorize('update', [PositionHistory::class, $this->positionHistory]);

            DB::beginTransaction();

            $this->positionHistory->update([
                'position_name' => $this->position_name,
                'work_unit' => $this->work_unit,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'company' => $this->company,
            ]);

            DB::commit();

            return redirect()->route('position-histories.index')
                ->success('Position history has been updated successfully.');
        } catch (ValidationException $e) {
            DB::rollBack();
            Toaster::error('Validation failed: ' . $e->getMessage());
            throw $e;
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return $this->unauthorizedAccess('edit position history');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException('edit position history', $e->getMessage());
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
