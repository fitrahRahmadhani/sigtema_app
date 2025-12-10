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
#[Title('Create Psychogram Result')]
class Create extends Component
{
    #[Validate('required|exists:companies,id')]
    public $company_id = '';

    #[Validate('required|exists:employees,id')]
    public $employee_id = '';

    public $aspects = [];
    public $availableAspects = [];

    public function mount()
    {
        $this->aspects = [
            ['aspect_id' => '', 'score' => 3] // Default satu row dengan score 3
        ];
    }

    public function save()
    {
        $this->validate([
            'company_id' => 'required|exists:companies,id',
            'employee_id' => 'required|exists:employees,id',
            'aspects' => 'required|array|min:1',
            'aspects.*.aspect_id' => 'required|exists:psychogram_aspects,id',
            'aspects.*.score' => 'required|integer|min:1|max:5',
        ]);

        try {
            $employee = Employee::find($this->employee_id);
            $existingAspects = [];
            $errors = [];

            foreach ($this->aspects as $index => $aspectData) {
                // Cek apakah aspect sudah dipilih di row lain
                if (in_array($aspectData['aspect_id'], $existingAspects)) {
                    $errors[] = "Aspect sudah dipilih di baris lain.";
                    $this->addError("aspects.{$index}.aspect_id", 'Aspect ini sudah dipilih di baris lain.');
                    continue;
                }
                $existingAspects[] = $aspectData['aspect_id'];

                // Cek apakah kombinasi employee_id dan aspect_id sudah ada
                $exists = PsychogramResult::where('employee_id', $this->employee_id)
                    ->where('aspect_id', $aspectData['aspect_id'])
                    ->exists();

                if ($exists) {
                    $errors[] = "Score untuk aspect ini sudah ada.";
                    $this->addError("aspects.{$index}.aspect_id", 'Score untuk aspect ini sudah ada untuk employee ini.');
                    continue;
                }

                // Validasi aspect availability untuk employee's company
                $aspect = PsychogramAspect::find($aspectData['aspect_id']);

                if ($aspect->company_id && $aspect->company_id != $employee->company_id) {
                    $errors[] = "Aspect tidak tersedia untuk company employee ini.";
                    $this->addError("aspects.{$index}.aspect_id", 'Aspect ini tidak tersedia untuk company employee ini.');
                    continue;
                }
            }

            if (!empty($errors)) {
                Toaster::error('Terdapat error dalam data input.');
                return;
            }

            // Create all results
            foreach ($this->aspects as $aspectData) {
                PsychogramResult::create([
                    'employee_id' => $this->employee_id,
                    'aspect_id' => $aspectData['aspect_id'],
                    'score' => $aspectData['score'],
                ]);
            }

            return redirect()->route('psychogram-results.index')->success('Psychogram results created successfully');
        } catch (\Exception $e) {
            Toaster::error('Failed to create psychogram results: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('psychogram-results.index');
    }

    public function addAspect()
    {
        $this->aspects[] = ['aspect_id' => '', 'score' => 3];
    }

    public function removeAspect($index)
    {
        if (count($this->aspects) > 1) {
            unset($this->aspects[$index]);
            $this->aspects = array_values($this->aspects); // Reset array keys
        }
    }

    public function updatedCompanyId($value)
    {
        $this->employee_id = '';
        $this->aspects = [['aspect_id' => '', 'score' => 3]];
        $this->availableAspects = [];
    }

    public function updatedEmployeeId($value)
    {
        $this->aspects = [['aspect_id' => '', 'score' => 3]];

        if ($value) {
            $employee = Employee::find($value);
            if ($employee && $employee->company_id) {
                $this->availableAspects = PsychogramAspect::where('is_active', true)
                    ->where(function ($query) use ($employee) {
                        $query->whereNull('company_id')
                            ->orWhere('company_id', $employee->company_id);
                    })
                    ->orderBy('aspect_name')
                    ->get();
            } else {
                $this->availableAspects = PsychogramAspect::where('is_active', true)
                    ->whereNull('company_id')
                    ->orderBy('aspect_name')
                    ->get();
            }
        } else {
            $this->availableAspects = [];
        }
    }

    public function render()
    {
        $companies = Company::orderBy('name')->get();

        $employees = $this->company_id
            ? Employee::where('company_id', $this->company_id)->orderBy('name')->get()
            : [];

        $selectedEmployee = $this->employee_id ? Employee::find($this->employee_id) : null;

        return view('livewire.pages.psychogram-results.create', [
            'companies' => $companies,
            'employees' => $employees,
            'selectedEmployee' => $selectedEmployee,
        ]);
    }
}
