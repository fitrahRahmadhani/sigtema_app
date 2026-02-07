<?php

namespace App\Livewire\Pages\Requirements\Positions;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Position;
use App\Models\Company;
use App\Models\PositionRequirementSetting;
use Illuminate\Support\Facades\Auth;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Create Position Requirements')]
class Create extends Component
{
    public $position_id = null;
    public $company_id = null;
    public $selectedCompany = null;
    public $isSuperadmin = false;
    public $positionFromUrl = false; // Flag untuk cek apakah position dari URL

    // Position search properties
    public $positionSearch = '';
    public $showPositionDropdown = false;
    public $selectedPosition = null;
    public $highlightedIndex = 0;

    // Dynamic requirements
    public $requirements = [
        ['name' => '', 'value' => '']
    ];

    // Minimum karakter untuk pencarian
    private $minSearchLength = 5;

    public function mount()
    {
        $user = Auth::user();
        $this->isSuperadmin = $user->hasRole('superadmin');

        // Set company_id based on user role
        if ($this->isSuperadmin) {
            $companyId = session('requirements_company_id');

            if (!$companyId) {
                Toaster::error('Please select a company first.');
                return redirect()->route('requirements.position');
            }
        } else {
            $companyId = $user->company_id;
        }

        $this->selectedCompany = Company::find($companyId);

        if (!$this->selectedCompany) {
            Toaster::error('Company not found.');
            return redirect()->route('requirements.position');
        }

        $this->company_id = $companyId;

        // Check if position_id is provided in URL
        $urlPositionId = request()->query('position_id');

        if ($urlPositionId) {
            $position = Position::find($urlPositionId);

            if (!$position) {
                Toaster::error('Position not found.');
                return redirect()->route('requirements.position');
            }

            // Validasi bahwa position milik company yang sesuai
            if ($position->company_id != $this->company_id) {
                Toaster::error('Position does not belong to the selected company.');
                return redirect()->route('requirements.position');
            }

            // Check if requirements already exist for this position
            $existingRequirements = PositionRequirementSetting::where('position_id', $position->id)->exists();
            if ($existingRequirements) {
                Toaster::warning('Requirements already exist for this position.');
                return redirect()->route('requirements.position.edit', ['position' => $position->id]);
            }

            // Set position dari URL (read-only)
            $this->selectedPosition = $position;
            $this->position_id = $position->id;
            $this->positionFromUrl = true;
            $this->positionSearch = $position->name . ' (' . $position->position_code . ')';
        }
    }

    public function updatedPositionSearch($value)
    {
        // Jika position dari URL, tidak boleh search
        if ($this->positionFromUrl) {
            return;
        }

        // Reset highlight
        $this->highlightedIndex = 0;

        // Tampilkan dropdown hanya jika ada input minimal 5 karakter
        if (strlen(trim($value)) >= $this->minSearchLength) {
            $this->showPositionDropdown = true;
        } else {
            $this->showPositionDropdown = false;
        }
    }

    public function getFilteredPositionsProperty()
    {
        // Jika position dari URL, tidak ada filtered positions
        if ($this->positionFromUrl) {
            return collect();
        }

        if (!$this->company_id) {
            return collect();
        }

        $searchTerm = trim($this->positionSearch);

        // Hanya lakukan pencarian jika input >= 5 karakter
        if (strlen($searchTerm) < $this->minSearchLength) {
            return collect();
        }

        // Pencarian dengan minimal 5 karakter
        $searchTermLower = strtolower($searchTerm);

        return Position::where('company_id', $this->company_id)
            ->where(function ($query) use ($searchTermLower) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . $searchTermLower . '%'])
                    ->orWhereRaw('LOWER(position_code) LIKE ?', ['%' . $searchTermLower . '%']);
            })
            ->orderBy('position_code')
            ->limit(20)
            ->get();
    }

    public function selectPosition($positionId)
    {
        // Jika position dari URL, tidak boleh select position lain
        if ($this->positionFromUrl) {
            return;
        }

        $position = Position::find($positionId);

        if ($position) {
            $this->selectedPosition = $position;
            $this->position_id = $position->id;
            $this->positionSearch = $position->name . ' (' . $position->position_code . ')';
            $this->showPositionDropdown = false;
            $this->highlightedIndex = 0;
        }
    }

    public function clearPositionSelection()
    {
        // Jika position dari URL, tidak boleh clear
        if ($this->positionFromUrl) {
            return;
        }

        $this->resetPositionSelection();
    }

    private function resetPositionSelection()
    {
        $this->selectedPosition = null;
        $this->position_id = null;
        $this->positionSearch = '';
        $this->showPositionDropdown = false;
        $this->highlightedIndex = 0;
    }

    public function incrementHighlight()
    {
        if ($this->positionFromUrl) {
            return;
        }

        $count = $this->filteredPositions->count();
        if ($count > 0) {
            $this->highlightedIndex = min($this->highlightedIndex + 1, $count - 1);
        }
    }

    public function decrementHighlight()
    {
        if ($this->positionFromUrl) {
            return;
        }

        $this->highlightedIndex = max($this->highlightedIndex - 1, 0);
    }

    public function selectHighlighted()
    {
        if ($this->positionFromUrl) {
            return;
        }

        $positions = $this->filteredPositions;

        if ($positions->count() > 0 && isset($positions[$this->highlightedIndex])) {
            $this->selectPosition($positions[$this->highlightedIndex]->id);
        }
    }

    public function closeDropdown()
    {
        $this->showPositionDropdown = false;
    }

    public function openDropdown()
    {
        // Jika position dari URL, tidak bisa buka dropdown
        if ($this->positionFromUrl) {
            return;
        }

        if ($this->company_id && !$this->selectedPosition && strlen(trim($this->positionSearch)) >= $this->minSearchLength) {
            $this->showPositionDropdown = true;
        }
    }

    public function addRequirementRow()
    {
        $this->requirements[] = ['name' => '', 'value' => ''];
    }

    public function removeRequirementRow($index)
    {
        if (count($this->requirements) > 1) {
            unset($this->requirements[$index]);
            $this->requirements = array_values($this->requirements);
        }
    }

    public function save()
    {
        // Validate position selection
        if (!$this->position_id) {
            Toaster::warning('Please select a position.');
            return;
        }

        // Validate requirements
        $validRequirements = [];
        foreach ($this->requirements as $index => $requirement) {
            $name = trim($requirement['name'] ?? '');
            $value = trim($requirement['value'] ?? '');

            // Skip empty rows
            if (empty($name) && empty($value)) {
                continue;
            }

            // Validate that name is not empty
            if (empty($name)) {
                Toaster::warning("Requirement name cannot be empty in row " . ($index + 1));
                return;
            }

            $validRequirements[] = [
                'name' => $name,
                'value' => $value
            ];
        }

        // Check if there's at least one valid requirement
        if (empty($validRequirements)) {
            Toaster::warning('Please add at least one requirement.');
            return;
        }

        // Check if requirements already exist for this position (double check)
        $existingRequirements = PositionRequirementSetting::where('position_id', $this->position_id)->exists();
        if ($existingRequirements) {
            Toaster::warning('Requirements already exist for this position. Please use the edit page instead.');
            return redirect()->route('requirements.position.edit', ['position' => $this->position_id]);
        }

        try {
            // Save all requirements
            foreach ($validRequirements as $requirement) {
                PositionRequirementSetting::create([
                    'position_id' => $this->position_id,
                    'position_code' => $this->selectedPosition->position_code,
                    'name' => $requirement['name'],
                    'value' => $requirement['value']
                ]);
            }

            Toaster::success(count($validRequirements) . ' requirements created successfully!');
            return redirect()->route('requirements.position');
        } catch (\Exception $e) {
            Toaster::error('Failed to create requirements: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('requirements.position');
    }

    public function render()
    {
        return view('livewire.pages.requirements.positions.create');
    }
}
