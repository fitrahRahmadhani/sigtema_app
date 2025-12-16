<?php

namespace App\Livewire\Pages\VacantPositions;

use Livewire\Component;
use App\Models\Position;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithPagination;

    #[Title('Vacant Positions')]

    public string $search = '';
    public string $filterDepartment = '';
    public string $filterUnit = '';

    protected $queryString = ['search', 'filterDepartment', 'filterUnit'];

    public array $allPositions = [];

    public function mount(): void
    {
        $this->allPositions = Position::where('company_id', Auth::user()->company_id)->where('employee_id', null)->get()->toArray();
        if (Auth::user()->isSuperadmin) {
            $this->allPositions = Position::where('employee_id', null)->get()->toArray();
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterDepartment(): void
    {
        $this->resetPage();
    }

    public function updatedFilterUnit(): void
    {
        $this->resetPage();
    }

    public function getFilteredPositions()
    {
        return collect($this->allPositions)
            ->when($this->search, function ($collection) {
                return $collection->filter(function ($item) {
                    return str_contains(strtolower($item['name']), strtolower($this->search)) ||
                        str_contains(strtolower($item['code']), strtolower($this->search));
                });
            })
            ->when($this->filterDepartment, function ($collection) {
                return $collection->where('department', $this->filterDepartment);
            })
            ->when($this->filterUnit, function ($collection) {
                return $collection->where('unit', $this->filterUnit);
            });
    }

    public function getUniqueDepartments()
    {
        return collect($this->allPositions)
            ->pluck('department')
            ->unique()
            ->sort()
            ->values();
    }

    public function getUniqueUnits()
    {
        return collect($this->allPositions)
            ->pluck('unit')
            ->unique()
            ->sort()
            ->values();
    }

    public function render()
    {
        $filtered = $this->getFilteredPositions();
        $positions = $filtered->forPage($this->getPage(), 10);
        $total = $filtered->count();

        return view('livewire.pages.vacant-positions.index', [
            'positions' => $positions,
            'total' => $total,
            'departments' => $this->getUniqueDepartments(),
            'units' => $this->getUniqueUnits(),
        ]);
    }

    private function getPage()
    {
        return request()->query('page', 1);
    }
}
