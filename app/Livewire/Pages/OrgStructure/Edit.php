<?php

namespace App\Livewire\Pages\OrgStructure;

use App\Livewire\Traits\HandleException;
use App\Livewire\Traits\UnauthorizedAccess;
use App\Models\EmployeeHierarchy;
use App\Models\Position;
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
#[Title('Edit Employee Hierarchy')]
class Edit extends Component
{
    use UnauthorizedAccess, HandleException;

    public User $user;
    public EmployeeHierarchy $hierarchy;
    public $company;
    public $company_id;
    public $position_id;
    public $superior_id;
    public $relation_type = 'direct';

    // Position search
    public $positionSearch           = '';
    public $showPositionDropdown     = false;
    public $selectedPosition         = null;
    public $positionHighlightedIndex = 0;

    // Superior search
    public $superiorSearch           = '';
    public $showSuperiorDropdown     = false;
    public $selectedSuperior         = null;
    public $superiorHighlightedIndex = 0;

    private $minSearchLength = 3;

    public function mount(EmployeeHierarchy $hierarchy)
    {
        $this->user      = Auth::user();
        $this->hierarchy = $hierarchy;

        // Ambil company langsung dari relasi hierarchy
        $this->company    = $hierarchy->company;
        $this->company_id = $hierarchy->company_id;

        // Pre-fill existing data
        $this->relation_type = $hierarchy->relation_type;

        // Pre-fill position
        $position = Position::select(['id', 'name', 'position_code'])
            ->find($hierarchy->position_id);
        if ($position) {
            $this->selectedPosition = $position;
            $this->position_id      = $position->id;
            $this->positionSearch   = $position->name . ' (' . $position->position_code . ')';
        }

        // Pre-fill superior
        $superior = Position::select(['id', 'name', 'position_code'])
            ->find($hierarchy->superior_id);
        if ($superior) {
            $this->selectedSuperior = $superior;
            $this->superior_id      = $superior->id;
            $this->superiorSearch   = $superior->name . ' (' . $superior->position_code . ')';
        }
    }

    protected function rules(): array
    {
        return [
            'company_id'    => ['required', 'exists:companies,id'],
            'position_id'   => ['required', 'exists:positions,id', 'different:superior_id'],
            'superior_id'   => ['required', 'exists:positions,id', 'different:position_id'],
            'relation_type' => ['required', 'string', 'max:255'],
        ];
    }

    protected function messages(): array
    {
        return [
            'company_id.required'    => 'Company is required.',
            'company_id.exists'      => 'Selected company does not exist.',
            'position_id.required'   => 'Position is required.',
            'position_id.exists'     => 'Selected position does not exist.',
            'position_id.different'  => 'Position and superior cannot be the same.',
            'superior_id.required'   => 'Superior position is required.',
            'superior_id.exists'     => 'Selected superior does not exist.',
            'superior_id.different'  => 'Superior and position cannot be the same.',
            'relation_type.required' => 'Relation type is required.',
        ];
    }

    public function getFilteredPositionsProperty()
    {
        if (!$this->company_id || strlen(trim($this->positionSearch)) < $this->minSearchLength) {
            return collect();
        }

        return Position::where('company_id', $this->company_id)
            ->where(function ($q) {
                $q->where('name', 'LIKE', '%' . $this->positionSearch . '%')
                    ->orWhere('position_code', 'LIKE', '%' . $this->positionSearch . '%');
            })
            ->select(['id', 'name', 'position_code'])
            ->orderBy('name')
            ->limit(10)
            ->get();
    }

    public function getFilteredSuperiorsProperty()
    {
        if (!$this->company_id || !$this->position_id || strlen(trim($this->superiorSearch)) < $this->minSearchLength) {
            return collect();
        }

        return Position::where('company_id', $this->company_id)
            ->where('id', '!=', $this->position_id)
            ->where(function ($q) {
                $q->where('name', 'LIKE', '%' . $this->superiorSearch . '%')
                    ->orWhere('position_code', 'LIKE', '%' . $this->superiorSearch . '%');
            })
            ->select(['id', 'name', 'position_code'])
            ->orderBy('name')
            ->limit(10)
            ->get();
    }

    public function updatedPositionSearch($value)
    {
        $this->positionHighlightedIndex = 0;
        $this->showPositionDropdown     = strlen(trim($value)) >= $this->minSearchLength;
    }

    public function selectPosition(int $id): void
    {
        $position = Position::select(['id', 'name', 'position_code'])->find($id);
        if ($position) {
            $this->selectedPosition         = $position;
            $this->position_id              = $position->id;
            $this->positionSearch           = $position->name . ' (' . $position->position_code . ')';
            $this->showPositionDropdown     = false;
            $this->positionHighlightedIndex = 0;
            $this->clearSuperiorSelection();
        }
    }

    public function clearPositionSelection(): void
    {
        $this->selectedPosition         = null;
        $this->position_id              = null;
        $this->positionSearch           = '';
        $this->showPositionDropdown     = false;
        $this->positionHighlightedIndex = 0;
        $this->clearSuperiorSelection();
    }

    public function openPositionDropdown(): void
    {
        if ($this->company_id && !$this->selectedPosition && strlen(trim($this->positionSearch)) >= $this->minSearchLength) {
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

    public function updatedSuperiorSearch($value)
    {
        $this->superiorHighlightedIndex = 0;
        $this->showSuperiorDropdown     = strlen(trim($value)) >= $this->minSearchLength;
    }

    public function selectSuperior(int $id): void
    {
        $superior = Position::select(['id', 'name', 'position_code'])->find($id);
        if ($superior) {
            $this->selectedSuperior         = $superior;
            $this->superior_id              = $superior->id;
            $this->superiorSearch           = $superior->name . ' (' . $superior->position_code . ')';
            $this->showSuperiorDropdown     = false;
            $this->superiorHighlightedIndex = 0;
        }
    }

    public function clearSuperiorSelection(): void
    {
        $this->selectedSuperior         = null;
        $this->superior_id              = null;
        $this->superiorSearch           = '';
        $this->showSuperiorDropdown     = false;
        $this->superiorHighlightedIndex = 0;
    }

    public function openSuperiorDropdown(): void
    {
        if ($this->position_id && !$this->selectedSuperior && strlen(trim($this->superiorSearch)) >= $this->minSearchLength) {
            $this->showSuperiorDropdown = true;
        }
    }

    public function closeSuperiorDropdown(): void
    {
        $this->showSuperiorDropdown = false;
    }

    public function incrementSuperiorHighlight(): void
    {
        $count = $this->filteredSuperiors->count();
        if ($count > 0) {
            $this->superiorHighlightedIndex = min($this->superiorHighlightedIndex + 1, $count - 1);
        }
    }

    public function decrementSuperiorHighlight(): void
    {
        $this->superiorHighlightedIndex = max($this->superiorHighlightedIndex - 1, 0);
    }

    public function selectHighlightedSuperior(): void
    {
        $superiors = $this->filteredSuperiors;
        if ($superiors->count() > 0 && isset($superiors[$this->superiorHighlightedIndex])) {
            $this->selectSuperior($superiors[$this->superiorHighlightedIndex]->id);
        }
    }

    public function save()
    {
        try {
            $this->validate();
            $this->authorize('update', [EmployeeHierarchy::class, $this->hierarchy]);

            $exists = EmployeeHierarchy::where('company_id', $this->company_id)
                ->where('position_id', $this->position_id)
                ->where('superior_id', $this->superior_id)
                ->where('id', '!=', $this->hierarchy->id)
                ->exists();

            if ($exists) {
                Toaster::error('This hierarchy relationship already exists.');
                return;
            }
            DB::beginTransaction();

            $this->hierarchy->update([
                'position_id'   => $this->position_id,
                'superior_id'   => $this->superior_id,
                'relation_type' => $this->relation_type,
            ]);

            DB::commit();

            return redirect()->route('organization-structure.table')
                ->success('Hierarchy updated successfully.');
        } catch (ValidationException $e) {
            DB::rollBack();
            Toaster::error('Validation failed: ' . $e->getMessage());
            throw $e;
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return $this->unauthorizedAccess('edit hierarchy');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException('edit hierarchy', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pages.org-structure.edit');
    }
}
