<?php

namespace App\Livewire\Pages\OrgStructure;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Position;
use Livewire\Attributes\Title;
use App\Models\EmployeeHierarchy;

#[Title('Organization Structure')]
class Index extends Component
{
    public $zoom = 100;
    public $nodes = [];
    public $companyId;

    public function mount($companyId = null)
    {
        // Ambil company_id dari user yang sedang login atau parameter
        $this->companyId = 1;
        $this->initializeChart();
    }

    private function initializeChart()
    {
        // Ambil semua hierarki untuk company ini
        $hierarchies = EmployeeHierarchy::where('company_id', $this->companyId)
            ->with(['position.employee', 'superior'])
            ->get();

        // Ambil semua position yang terlibat
        $positionIds = $hierarchies->pluck('position_id')
            ->merge($hierarchies->pluck('superior_id'))
            ->unique();

        $positions = Position::whereIn('id', $positionIds)
            ->with(['employee' => function ($query) {
                $query->where('company_id', $this->companyId)
                    ->latest('created_at');
            }])
            ->get();

        // Build nodes array
        $this->nodes = [];
        $nodeIdCounter = 1;
        $positionToNodeId = [];

        // Cari root positions (yang tidak ada sebagai child di hierarki)
        $childPositionIds = $hierarchies->pluck('position_id')->toArray();
        $rootPositionIds = $positionIds->diff($childPositionIds)->values();

        // Jika tidak ada root yang jelas, ambil position dengan level tertinggi
        if ($rootPositionIds->isEmpty()) {
            $rootPositionIds = collect([$hierarchies->first()->superior_id]);
        }

        // Function untuk build tree recursively
        $buildNode = function ($positionId, $parentNodeId = null, $level = 0) use (&$buildNode, &$nodeIdCounter, &$positionToNodeId, $positions, $hierarchies) {
            // Cek apakah position ini sudah dibuat nodenya (prevent duplicate)
            if (isset($positionToNodeId[$positionId])) {
                return;
            }

            $position = $positions->firstWhere('id', $positionId);
            if (!$position) return;

            // Ambil employee yang menduduki position ini
            $employee = $position->employee;

            $nodeId = $nodeIdCounter++;
            $positionToNodeId[$positionId] = $nodeId;

            // Hitung total children
            $childrenCount = $hierarchies->where('superior_id', $positionId)->count();

            $this->nodes[] = [
                'id' => $nodeId,
                'position_id' => $positionId,
                'name' => $employee ? $employee->name : 'Vacant Position',
                'title' => $position->name,
                'department' => $position->department->name ?? '-',
                'parent_id' => $parentNodeId,
                'visible' => $level === 0 ? true : false, // Only root visible
                'collapsed' => $childrenCount > 0, // Collapsed if has children
                'level' => $level,
                'photo' => $employee ? $employee->profile_path : null,
                'employee_id' => $employee ? $employee->id : null,
            ];

            // Build children nodes
            $children = $hierarchies->where('superior_id', $positionId);
            foreach ($children as $child) {
                $buildNode($child->position_id, $nodeId, $level + 1);
            }
        };

        // Build tree dari root positions
        foreach ($rootPositionIds as $rootId) {
            $buildNode($rootId);
        }
    }

    public function toggleNode($nodeId)
    {
        $nodeIndex = $this->findNodeIndex($nodeId);

        if ($nodeIndex !== false) {
            // Toggle collapsed state
            $this->nodes[$nodeIndex]['collapsed'] = !$this->nodes[$nodeIndex]['collapsed'];

            // Update children visibility
            $this->updateChildrenVisibility($nodeId);
        }
    }

    private function updateChildrenVisibility($parentId)
    {
        $parent = $this->getNode($parentId);
        if (!$parent) return;

        $isCollapsed = $parent['collapsed'];

        // Update direct children only
        foreach ($this->nodes as $key => $node) {
            if ($node['parent_id'] === $parentId) {
                if ($isCollapsed) {
                    // Hide children when parent collapsed
                    $this->nodes[$key]['visible'] = false;
                    // Also collapse children's children
                    $this->hideAllDescendants($node['id']);
                } else {
                    // Show direct children when parent expanded
                    $this->nodes[$key]['visible'] = true;
                    // Keep children collapsed (user must click to expand them)
                    $this->nodes[$key]['collapsed'] = true;
                }
            }
        }
    }

    private function hideAllDescendants($nodeId)
    {
        foreach ($this->nodes as $key => $node) {
            if ($node['parent_id'] === $nodeId) {
                $this->nodes[$key]['visible'] = false;
                $this->nodes[$key]['collapsed'] = true;
                // Recursively hide grandchildren
                $this->hideAllDescendants($node['id']);
            }
        }
    }

    private function findNodeIndex($nodeId)
    {
        foreach ($this->nodes as $index => $node) {
            if ($node['id'] === $nodeId) {
                return $index;
            }
        }
        return false;
    }

    public function getNode($nodeId)
    {
        return collect($this->nodes)->firstWhere('id', $nodeId);
    }

    public function hasChildren($nodeId)
    {
        return collect($this->nodes)->where('parent_id', $nodeId)->isNotEmpty();
    }

    public function hasVisibleChildren($nodeId)
    {
        return collect($this->nodes)
            ->where('parent_id', $nodeId)
            ->where('visible', true)
            ->isNotEmpty();
    }

    public function getChildNodes($nodeId)
    {
        return collect($this->nodes)
            ->where('parent_id', $nodeId)
            ->where('visible', true)
            ->values()
            ->toArray();
    }

    public function zoomIn()
    {
        if ($this->zoom < 200) {
            $this->zoom += 10;
        }
    }

    public function zoomOut()
    {
        if ($this->zoom > 50) {
            $this->zoom -= 10;
        }
    }

    public function showEmployeeDetail($id)
    {
        $employee = Employee::find($id);
        return redirect()->route('employees.show', [$employee->slug]);
    }

    public function resetZoom()
    {
        $this->zoom = 100;
    }

    public function refreshChart()
    {
        $this->initializeChart();
    }

    public function render()
    {
        return view('livewire.pages.org-structure.index');
    }
}
