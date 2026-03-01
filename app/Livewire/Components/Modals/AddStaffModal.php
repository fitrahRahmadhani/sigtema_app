<?php

namespace App\Livewire\Components\Modals;

use App\Livewire\Traits\HandleException;
use App\Livewire\Traits\UnauthorizedAccess;
use App\Models\Company;
use App\Models\EmployeeHierarchy;
use App\Models\Position;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class AddStaffModal extends Component
{
    use UnauthorizedAccess, HandleException;

    public $show                 = false;
    public $company              = null;
    public $companyId            = null;
    public $selectedSuperiorId   = null;
    public $selectedSuperiorName = '';

    public $form = [
        'company_id'    => '',
        'position_id'   => '',
        'superior_id'   => '',
        'relation_type' => 'direct',
    ];

    // Position search
    public $positionSearch           = '';
    public $showPositionDropdown     = false;
    public $selectedPosition         = null;
    public $positionHighlightedIndex = 0;

    private $minSearchLength = 3;

    protected $listeners = [
        'openAddStaffModal' => 'open',
        'refreshPositions'  => '$refresh',
    ];

    protected function rules(): array
    {
        return [
            'form.company_id'    => 'required|exists:companies,id',
            'form.position_id'   => [
                'required',
                'exists:positions,id',
                'different:form.superior_id',
                function ($attribute, $value, $fail) {
                    $exists = EmployeeHierarchy::where('company_id', $this->form['company_id'])
                        ->where('position_id', $value)
                        ->exists();
                    if ($exists) {
                        $fail('This position already has a hierarchy relationship.');
                    }
                },
            ],
            'form.superior_id'   => 'required|exists:positions,id|different:form.position_id',
            'form.relation_type' => 'required|string|max:255',
        ];
    }

    protected function messages(): array
    {
        return [
            'form.company_id.required'    => 'Company is required.',
            'form.company_id.exists'      => 'Selected company does not exist.',
            'form.position_id.required'   => 'Staff position is required.',
            'form.position_id.exists'     => 'Selected position does not exist.',
            'form.position_id.different'  => 'Position and superior cannot be the same.',
            'form.superior_id.required'   => 'Superior position is required.',
            'form.superior_id.exists'     => 'Selected superior does not exist.',
            'form.superior_id.different'  => 'Superior and position cannot be the same.',
            'form.relation_type.required' => 'Relation type is required.',
            'form.relation_type.max'      => 'Relation type cannot exceed 255 characters.',
        ];
    }

    public function open($superiorPositionId = null, $companyId = null)
    {
        $this->selectedSuperiorId        = $superiorPositionId;
        $this->form['superior_id']       = $superiorPositionId;
        $this->form['relation_type']     = 'direct';

        // Ambil nama superior
        if ($superiorPositionId) {
            $superior = Position::find($superiorPositionId);
            $this->selectedSuperiorName = $superior
                ? $superior->name . ' - ' . ($superior->getWorkUnitAttribute() ?? 'No Department')
                : 'Unknown Position';

            // Ambil company dari superior jika companyId tidak diberikan
            $companyId = $companyId ?? $superior?->company_id;
        } else {
            $this->selectedSuperiorName = '';
        }

        $this->companyId          = $companyId;
        $this->company            = $companyId ? Company::find($companyId) : null;
        $this->form['company_id'] = $companyId;

        $this->show = true;
    }

    public function close()
    {
        $this->show = false;
        $this->resetModal();
        $this->dispatch('addStaffModalClosed');
    }

    public function getFilteredPositionsProperty()
    {
        if (!$this->companyId || strlen(trim($this->positionSearch)) < $this->minSearchLength) {
            return collect();
        }

        // Exclude positions already in a hierarchy as child
        $existingChildIds = EmployeeHierarchy::where('company_id', $this->companyId)
            ->pluck('position_id')
            ->toArray();

        // Also exclude the superior itself
        if ($this->selectedSuperiorId) {
            $existingChildIds[] = $this->selectedSuperiorId;
        }

        return Position::where('company_id', $this->companyId)
            ->whereNotIn('id', $existingChildIds)
            ->where(function ($q) {
                $q->where('name', 'LIKE', '%' . $this->positionSearch . '%')
                    ->orWhere('position_code', 'LIKE', '%' . $this->positionSearch . '%');
            })
            ->select(['id', 'name', 'position_code'])
            ->orderBy('name')
            ->limit(10)
            ->get();
    }

    public function updatedPositionSearch($value): void
    {
        $this->positionHighlightedIndex = 0;
        $this->showPositionDropdown     = strlen(trim($value)) >= $this->minSearchLength;
    }

    public function selectPosition(int $id): void
    {
        $position = Position::select(['id', 'name', 'position_code'])->find($id);
        if ($position) {
            $this->selectedPosition         = $position;
            $this->form['position_id']      = $position->id;
            $this->positionSearch           = $position->name . ' (' . $position->position_code . ')';
            $this->showPositionDropdown     = false;
            $this->positionHighlightedIndex = 0;
        }
    }

    public function clearPositionSelection(): void
    {
        $this->selectedPosition         = null;
        $this->form['position_id']      = '';
        $this->positionSearch           = '';
        $this->showPositionDropdown     = false;
        $this->positionHighlightedIndex = 0;
    }

    public function openPositionDropdown(): void
    {
        if ($this->companyId && !$this->selectedPosition && strlen(trim($this->positionSearch)) >= $this->minSearchLength) {
            $this->showPositionDropdown = true;
        }
    }

    public function closePositionDropdown(): void
    {
        $this->showPositionDropdown = false;
    }

    public function incrementPositionHighlight(): void
    {
        $count = $this->filteredPositions->count();
        if ($count > 0) {
            $this->positionHighlightedIndex = min($this->positionHighlightedIndex + 1, $count - 1);
        }
    }

    public function decrementPositionHighlight(): void
    {
        $this->positionHighlightedIndex = max($this->positionHighlightedIndex - 1, 0);
    }

    public function selectHighlightedPosition(): void
    {
        $positions = $this->filteredPositions;
        if ($positions->count() > 0 && isset($positions[$this->positionHighlightedIndex])) {
            $this->selectPosition($positions[$this->positionHighlightedIndex]->id);
        }
    }

    public function save()
    {
        $exists = EmployeeHierarchy::where('company_id', $this->form['company_id'])
            ->where('position_id', $this->form['position_id'])
            ->where('superior_id', $this->form['superior_id'])
            ->exists();

        if ($exists) {
            Toaster::error('This hierarchy relationship already exists.');
            return;
        }

        try {
            $this->validate();
            $this->authorize('create', [EmployeeHierarchy::class, Auth::user()?->company]);

            DB::beginTransaction();
            EmployeeHierarchy::create([
                'company_id'    => $this->form['company_id'],
                'position_id'   => $this->form['position_id'],
                'superior_id'   => $this->form['superior_id'],
                'relation_type' => $this->form['relation_type'],
            ]);
            DB::commit();

            Toaster::success('Staff hierarchy created successfully.');
            $this->close();
            $this->dispatch('staffAdded');
        } catch (ValidationException $e) {
            DB::rollBack();
            Toaster::error('Validation failed: ' . $e->getMessage());
            throw $e;
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return $this->unauthorizedAccess('add staff');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException('add staff', $e->getMessage());
        }
    }

    private function resetModal(): void
    {
        $this->form = [
            'company_id'    => '',
            'position_id'   => '',
            'superior_id'   => $this->selectedSuperiorId,
            'relation_type' => 'direct',
        ];
        $this->company                  = null;
        $this->companyId                = null;
        $this->selectedSuperiorId       = null;
        $this->selectedSuperiorName     = '';
        $this->selectedPosition         = null;
        $this->positionSearch           = '';
        $this->showPositionDropdown     = false;
        $this->positionHighlightedIndex = 0;
    }

    public function render()
    {
        return view('livewire.components.modals.add-staff-modal');
    }
}
