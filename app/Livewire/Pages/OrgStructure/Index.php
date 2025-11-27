<?php

namespace App\Livewire\Pages\OrgStructure;

use App\Models\Company;
use Livewire\Component;
use App\Models\Employee;
use App\Models\Position;
use Livewire\Attributes\Title;
use Masmerise\Toaster\Toaster;
use App\Models\EmployeeHierarchy;
use App\Livewire\Traits\WithDeleteConfirmation;
use App\Livewire\Components\Modals\AddStaffModal;

#[Title('Organization Structure')]
class Index extends Component
{
    use WithDeleteConfirmation;

    public $zoom = 100;
    public $nodes = [];
    public $companyId;

    protected $listeners = [
        'refreshChart' => 'refreshChart',
        'staffAdded' => 'refreshChart' // Listen untuk event dari modal
    ];

    public function mount($companyId = null)
    {
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
        $buildNode = function ($positionId, $parentNodeId = null, $level = 0, $hierarchyId = null) use (&$buildNode, &$nodeIdCounter, &$positionToNodeId, $positions, $hierarchies) {
            // Cek apakah position ini sudah dibuat nodenya (prevent duplicate)
            if (isset($positionToNodeId[$positionId])) {
                return;
            }

            $position = $positions->firstWhere('id', $positionId);
            if (!$position) return;

            // Ambil employee yang menduduki position ini
            $employee = $position->employee;

            // Cari hierarchy_id untuk node ini
            // Untuk root nodes, hierarchy_id akan null
            // Untuk child nodes, cari hierarchy yang menghubungkan parent dan child
            $currentHierarchyId = $hierarchyId;
            if ($parentNodeId) {
                $parentPositionId = $this->getPositionIdFromNodeId($parentNodeId);
                $hierarchy = $hierarchies->firstWhere(function ($item) use ($positionId, $parentPositionId) {
                    return $item->position_id == $positionId && $item->superior_id == $parentPositionId;
                });
                $currentHierarchyId = $hierarchy ? $hierarchy->id : null;
            }

            $nodeId = $nodeIdCounter++;
            $positionToNodeId[$positionId] = $nodeId;

            // Hitung total children
            $childrenCount = $hierarchies->where('superior_id', $positionId)->count();

            $this->nodes[] = [
                'id' => $nodeId,
                'position_id' => $positionId,
                'name' => $employee ? $employee->name : 'Vacant Position',
                'title' => $position->name,
                'department' => $position->getWorkUnitAttribute(),
                'parent_id' => $parentNodeId,
                'visible' => $level === 0 ? true : false, // Only root visible
                'collapsed' => $childrenCount > 0, // Collapsed if has children
                'level' => $level,
                'photo' => $employee ? $employee->profile_path : null,
                'slug' => $employee ? $employee->slug : null,
                'hierarchy_id' => $currentHierarchyId, // Tambahkan ini
            ];

            // Build children nodes
            $children = $hierarchies->where('superior_id', $positionId);
            foreach ($children as $child) {
                $buildNode($child->position_id, $nodeId, $level + 1, $child->id);
            }
        };

        // Build tree dari root positions
        foreach ($rootPositionIds as $rootId) {
            $buildNode($rootId);
        }
    }

    // Helper method untuk mendapatkan position_id dari node_id
    private function getPositionIdFromNodeId($nodeId)
    {
        $node = collect($this->nodes)->firstWhere('id', $nodeId);
        return $node ? $node['position_id'] : null;
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

    public function resetZoom()
    {
        $this->zoom = 100;
    }

    public function refreshChart()
    {
        // Simpan state expanded/collapsed sebelum refresh
        $expandedNodes = collect($this->nodes)
            ->where('collapsed', false)
            ->pluck('position_id')
            ->toArray();

        // Refresh chart
        $this->initializeChart();

        // Restore expanded state
        foreach ($this->nodes as $index => $node) {
            if (in_array($node['position_id'], $expandedNodes)) {
                $this->nodes[$index]['collapsed'] = false;
                // Juga set children sebagai visible
                $this->setChildrenVisibility($node['id'], true);
            }
        }
    }

    /**
     * Helper untuk set visibility children
     */
    private function setChildrenVisibility($parentId, $visible)
    {
        foreach ($this->nodes as $index => $node) {
            if ($node['parent_id'] === $parentId) {
                $this->nodes[$index]['visible'] = $visible;
                // Recursively set grandchildren visibility jika parent expanded
                if (!$node['collapsed']) {
                    $this->setChildrenVisibility($node['id'], $visible);
                }
            }
        }
    }

    // Implement abstract methods from trait
    protected function getModelClass(): string
    {
        return EmployeeHierarchy::class;
    }

    protected function getModelDisplayName(): string
    {
        return 'Hierarchy';
    }

    /**
     * Override performDelete method dari trait
     * Tambahkan refreshChart() setelah delete
     */
    protected function performDelete($item): void
    {
        // Jalankan delete logic dari trait
        $item->delete();

        // Tambahkan refresh chart
        $this->refreshChart();
    }

    public function openAddStaffModal($superiorPositionId)
    {
        $this->dispatch('openAddStaffModal', superiorPositionId: $superiorPositionId)
            ->to(AddStaffModal::class);
    }

    public function render()
    {
        return view('livewire.pages.org-structure.index');
    }
}
