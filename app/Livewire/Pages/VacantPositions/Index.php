<?php

namespace App\Livewire\Pages\VacantPositions;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

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
        $this->allPositions = [
            ['id' => 1, 'name' => 'SM of WHRPG & Utility', 'code' => '50049503', 'unit' => 'Unit of WHRPG & Utility', 'department' => 'Department of Mining & Raw Material', 'candidates' => 5],
            ['id' => 2, 'name' => 'Senior Manager Operations', 'code' => '50049504', 'unit' => 'Unit of Operations', 'department' => 'Department of Operations', 'candidates' => 0],
            ['id' => 3, 'name' => 'HR Manager', 'code' => '50049505', 'unit' => 'Unit of Human Resources', 'department' => 'Department of Human Resources', 'candidates' => 4],
            ['id' => 4, 'name' => 'Finance Director', 'code' => '50049506', 'unit' => 'Unit of Finance', 'department' => 'Department of Finance & Administration', 'candidates' => 2],
            ['id' => 5, 'name' => 'IT Systems Administrator', 'code' => '50049507', 'unit' => 'Unit of Information Technology', 'department' => 'Department of Information Technology', 'candidates' => 6],
            ['id' => 6, 'name' => 'Safety Manager', 'code' => '50049508', 'unit' => 'Unit of Safety & Health', 'department' => 'Department of Mining & Raw Material', 'candidates' => 0],
            ['id' => 7, 'name' => 'Production Supervisor', 'code' => '50049509', 'unit' => 'Unit of Production', 'department' => 'Department of Production', 'candidates' => 4],
            ['id' => 8, 'name' => 'Quality Assurance Lead', 'code' => '50049510', 'unit' => 'Unit of Quality Control', 'department' => 'Department of Quality & Compliance', 'candidates' => 5],
            ['id' => 9, 'name' => 'Maintenance Engineer', 'code' => '50049511', 'unit' => 'Unit of Maintenance', 'department' => 'Department of Operations', 'candidates' => 3],
            ['id' => 10, 'name' => 'Project Manager', 'code' => '50049512', 'unit' => 'Unit of Projects', 'department' => 'Department of Projects', 'candidates' => 2],
            ['id' => 11, 'name' => 'HR Manager', 'code' => '50049513', 'unit' => 'Unit of Human Resources', 'department' => 'Department of Human Resources', 'candidates' => 4],
            ['id' => 12, 'name' => 'Finance Director', 'code' => '50049514', 'unit' => 'Unit of Finance', 'department' => 'Department of Finance & Administration', 'candidates' => 2],
            ['id' => 13, 'name' => 'IT Systems Administrator', 'code' => '50049515', 'unit' => 'Unit of Information Technology', 'department' => 'Department of Information Technology', 'candidates' => 6],
        ];
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
