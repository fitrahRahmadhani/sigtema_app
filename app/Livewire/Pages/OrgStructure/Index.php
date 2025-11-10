<?php

namespace App\Livewire\Pages\OrgStructure;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Organization Structure')]
class Index extends Component
{
    public $zoom = 100;
    public $nodes = [];

    public function mount()
    {
        $this->initializeChart();
    }

    private function initializeChart()
    {
        $this->nodes = [
            [
                'id' => 1,
                'name' => 'Sarah Johnson',
                'title' => 'CEO',
                'department' => 'Executive',
                'parent_id' => null,
                'visible' => true,
                'collapsed' => true, // CEO mulai dalam keadaan collapsed
                'level' => 0,
                'photo' => null
            ],
            [
                'id' => 2,
                'name' => 'Mike Chen',
                'title' => 'CTO',
                'department' => 'Technology',
                'parent_id' => 1,
                'visible' => false, // Children CEO hidden dulu
                'collapsed' => true, // Jika di-show, dia juga collapsed
                'level' => 1,
                'photo' => null
            ],
            [
                'id' => 3,
                'name' => 'Lisa Rodriguez',
                'title' => 'CFO',
                'department' => 'Finance',
                'parent_id' => 1,
                'visible' => false,
                'collapsed' => true,
                'level' => 1,
                'photo' => null
            ],
            [
                'id' => 4,
                'name' => 'David Kim',
                'title' => 'COO',
                'department' => 'Operations',
                'parent_id' => 1,
                'visible' => false,
                'collapsed' => true,
                'level' => 1,
                'photo' => null
            ],
            [
                'id' => 5,
                'name' => 'Alex Thompson',
                'title' => 'Dev Manager',
                'department' => 'Engineering',
                'parent_id' => 2,
                'visible' => false,
                'collapsed' => true,
                'level' => 2,
                'photo' => null
            ],
            [
                'id' => 6,
                'name' => 'Maria Garcia',
                'title' => 'Design Lead',
                'department' => 'Product Design',
                'parent_id' => 2,
                'visible' => false,
                'collapsed' => true,
                'level' => 2,
                'photo' => null
            ],
            [
                'id' => 7,
                'name' => 'James Wilson',
                'title' => 'Accounting Manager',
                'department' => 'Finance',
                'parent_id' => 3,
                'visible' => false,
                'collapsed' => true,
                'level' => 2,
                'photo' => null
            ],
            [
                'id' => 8,
                'name' => 'Emma Davis',
                'title' => 'Budget Analyst',
                'department' => 'Finance',
                'parent_id' => 3,
                'visible' => false,
                'collapsed' => true,
                'level' => 2,
                'photo' => null
            ],
            [
                'id' => 9,
                'name' => 'Robert Brown',
                'title' => 'Ops Manager',
                'department' => 'Operations',
                'parent_id' => 4,
                'visible' => false,
                'collapsed' => true,
                'level' => 2,
                'photo' => null
            ],
            [
                'id' => 10,
                'name' => 'Sophia Lee',
                'title' => 'Senior Developer',
                'department' => 'Engineering',
                'parent_id' => 5,
                'visible' => false,
                'collapsed' => true,
                'level' => 3,
                'photo' => null
            ],
            [
                'id' => 11,
                'name' => 'Daniel Martin',
                'title' => 'Frontend Lead',
                'department' => 'Engineering',
                'parent_id' => 5,
                'visible' => false,
                'collapsed' => true,
                'level' => 3,
                'photo' => null
            ],
            [
                'id' => 12,
                'name' => 'Karen White',
                'title' => 'Logistics Coordinator',
                'department' => 'Operations',
                'parent_id' => 9,
                'visible' => false,
                'collapsed' => true,
                'level' => 3,
                'photo' => null
            ]
        ];
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

    public function render()
    {
        return view('livewire.pages.org-structure.index');
    }
}
