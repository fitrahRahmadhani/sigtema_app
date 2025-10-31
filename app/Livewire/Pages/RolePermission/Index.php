<?php

namespace App\Livewire\Pages\RolePermission;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Spatie\Permission\Models\Role;
use Masmerise\Toaster\Toaster;
use App\Livewire\Traits\WithDeleteConfirmation;

#[Layout('components.layouts.app')]
#[Title('Role Management')]
class Index extends Component
{
    use WithPagination, WithDeleteConfirmation;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Implement abstract methods from trait
    protected function getModelClass(): string
    {
        return Role::class;
    }

    protected function getModelDisplayName(): string
    {
        return 'Role';
    }

    protected function performDelete($role): void
    {
        // Prevent deletion of super admin role
        if ($role->name === 'superadmin') {
            throw new \Exception('Cannot delete Super Admin role!');
        }

        $role->delete();
    }

    public function render()
    {
        $roles = Role::withCount('users')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.pages.role-permission.index', [
            'roles' => $roles,
        ]);
    }
}
