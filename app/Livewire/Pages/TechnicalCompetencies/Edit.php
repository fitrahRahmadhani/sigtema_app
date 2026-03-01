<?php

namespace App\Livewire\Pages\TechnicalCompetencies;

use App\Livewire\Traits\HandleException;
use App\Livewire\Traits\UnauthorizedAccess;
use App\Models\Employee;
use App\Models\TechnicalCompetency;
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
#[Title('Edit Technical Competency')]
class Edit extends Component
{
    use UnauthorizedAccess, HandleException;

    public TechnicalCompetency $technicalCompetency;

    public User $user;
    public $employee_id;
    public $name;
    public $issued_by;
    public $year;

    public $selectedCompany = null;
    public $selectedEmployee = null;
    public $isSuperadmin = false;

    public function mount(TechnicalCompetency $technical_competency)
    {
        $this->technicalCompetency = $technical_competency;

        // Load data ke form
        $this->employee_id = $technical_competency->employee_id;
        $this->name = $technical_competency->name;
        $this->issued_by = $technical_competency->issued_by;
        $this->year = $technical_competency->year;

        $this->user = Auth::user();
        $this->isSuperadmin = $this->user->hasRole('superadmin');

        // Load employee dan company (read-only)
        $this->selectedEmployee = Employee::find($this->employee_id);
        $this->selectedCompany = $this->selectedEmployee->company;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'issued_by' => 'required|string|max:255',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Competency name is required.',
            'issued_by.required' => 'Issued by is required.',
            'year.required' => 'Year is required.',
            'year.integer' => 'Year must be a valid number.',
            'year.min' => 'Year must be at least 2000.',
            'year.max' => 'Year cannot be more than ' . (date('Y') + 1) . '.',
        ];
    }

    public function save()
    {
        try {
            $this->validate();
            $this->authorize('update', [TechnicalCompetency::class, $this->technicalCompetency]);

            DB::beginTransaction();

            $this->technicalCompetency->update([
                'name' => $this->name,
                'issued_by' => $this->issued_by,
                'year' => $this->year,
            ]);

            DB::commit();

            return redirect()->route('technical-competencies.index')
                ->success('Technical competency has been updated successfully.');
        } catch (ValidationException $e) {
            DB::rollBack();
            Toaster::error('Validation failed: ' . $e->getMessage());
            throw $e;
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return $this->unauthorizedAccess('edit technical competency');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException('edit technical competency', $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('technical-competencies.index');
    }

    public function render()
    {
        return view('livewire.pages.technical-competencies.edit');
    }
}
