<?php

namespace App\Livewire\Pages\PsychogramResults;

use App\Livewire\Traits\HandleException;
use App\Livewire\Traits\UnauthorizedAccess;
use App\Models\Company;
use App\Models\Employee;
use App\Models\PsychogramAspect;
use App\Models\PsychogramResult;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Create Psychogram Result')]
class Create extends Component
{
    use UnauthorizedAccess, HandleException;

    public User $user;

    public $company_id = '';
    public $employee_id = '';

    public $aspects = [];
    public $availableAspects = [];

    public $selectedCompany = null;
    public $selectedEmployee = null;
    public $isSuperadmin = false;

    public $employeeSearch = '';
    public $showEmployeeDropdown = false;
    public $highlightedIndex = 0;

    private int $minSearchLength = 5;

    public function mount()
    {
        $this->user = Auth::user();
        $this->isSuperadmin = $this->user->hasRole('superadmin');

        $companyId = $this->isSuperadmin
            ? session('psychogram_results_company_id')
            : $this->user->company_id;

        if (!$companyId) {
            return redirect()->route('psychogram-results.index');
        }

        $this->selectedCompany = Company::find($companyId);

        if (!$this->selectedCompany) {
            return redirect()->route('psychogram-results.index');
        }

        $this->company_id = $companyId;

        $this->aspects = [
            ['aspect_id' => '', 'score' => 3]
        ];
    }

    public function rules()
    {
        return [
            'company_id' => 'required|exists:companies,id',
            'employee_id' => 'required|exists:employees,id',
            'aspects' => 'required|array|min:1',
            'aspects.*.aspect_id' => 'required|exists:psychogram_aspects,id|distinct',
            'aspects.*.score' => 'required|integer|min:1|max:5',
        ];
    }

    public function messages()
    {
        return [
            'aspects.required' => 'At least one aspect must be added.',
            'aspects.*.aspect_id.required' => 'Please select an aspect.',
            'aspects.*.aspect_id.distinct' => 'Each aspect can only be selected once.',
            'aspects.*.score.required' => 'Please provide a score.',
            'aspects.*.score.min' => 'Score must be at least 1.',
            'aspects.*.score.max' => 'Score must not exceed 5.',
        ];
    }

    public function updatedEmployeeSearch($value)
    {
        $this->highlightedIndex = 0;

        $this->showEmployeeDropdown =
            strlen(trim($value)) >= $this->minSearchLength;
    }

    public function openDropdown()
    {
        if (
            $this->company_id &&
            !$this->selectedEmployee &&
            strlen(trim($this->employeeSearch)) >= $this->minSearchLength
        ) {
            $this->showEmployeeDropdown = true;
        }
    }

    public function closeDropdown()
    {
        $this->showEmployeeDropdown = false;
    }

    public function getFilteredEmployeesProperty()
    {
        if (!$this->company_id) {
            return collect();
        }

        $search = trim($this->employeeSearch);

        if (strlen($search) < $this->minSearchLength) {
            return collect();
        }

        return Employee::where('company_id', $this->company_id)
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('employee_number', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->limit(20)
            ->get();
    }

    public function incrementHighlight()
    {
        $count = $this->filteredEmployees->count();
        if ($count > 0) {
            $this->highlightedIndex = min($this->highlightedIndex + 1, $count - 1);
        }
    }

    public function decrementHighlight()
    {
        $this->highlightedIndex = max($this->highlightedIndex - 1, 0);
    }

    public function selectHighlighted()
    {
        $employees = $this->filteredEmployees;

        if ($employees->count() > 0 && isset($employees[$this->highlightedIndex])) {
            $this->selectEmployee($employees[$this->highlightedIndex]->id);
        }
    }

    public function selectEmployee($employeeId)
    {
        $employee = Employee::where('id', $employeeId)
            ->where('company_id', $this->company_id)
            ->first();

        if (!$employee) {
            return;
        }

        $this->selectedEmployee = $employee;
        $this->employee_id = $employee->id;
        $this->employeeSearch = "{$employee->name} ({$employee->employee_number})";
        $this->showEmployeeDropdown = false;

        $this->loadAvailableAspects();
    }

    public function clearEmployeeSelection()
    {
        $this->selectedEmployee = null;
        $this->employee_id = '';
        $this->employeeSearch = '';
        $this->availableAspects = [];
        $this->aspects = [['aspect_id' => '', 'score' => 3]];
    }

    private function loadAvailableAspects()
    {
        $this->availableAspects = PsychogramAspect::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('company_id')
                    ->orWhere('company_id', $this->company_id);
            })
            ->orderBy('aspect_name')
            ->get();
    }

    public function addAspect()
    {
        if (!$this->employee_id) {
            return;
        }

        $this->aspects[] = ['aspect_id' => '', 'score' => 3];
    }

    public function removeAspect($index)
    {
        if (count($this->aspects) > 1) {
            unset($this->aspects[$index]);
            $this->aspects = array_values($this->aspects);
        }
    }

    public function save()
    {
        try {
            $this->validate();
            $this->authorize('create', [PsychogramResult::class, $this->user->company]);

            DB::beginTransaction();

            $insertData = collect($this->aspects)->map(function ($item) {
                return [
                    'employee_id' => $this->employee_id,
                    'aspect_id' => $item['aspect_id'],
                    'score' => $item['score'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            PsychogramResult::insert($insertData);

            DB::commit();

            return redirect()->route('psychogram-results.index')
                ->success('Psychogram results created successfully');
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return $this->unauthorizedAccess('create psychogram result');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException('create psychogram result', $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('psychogram-results.index');
    }

    public function render()
    {
        return view('livewire.pages.psychogram-results.create');
    }
}
