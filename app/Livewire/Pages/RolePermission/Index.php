<?php

namespace App\Livewire\Pages\RolePermission;

use App\Livewire\Traits\HandleException;
use App\Livewire\Traits\WithDeleteConfirmation;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Role & Permission Management')]
class Index extends Component
{
    use WithPagination, WithDeleteConfirmation, HandleException;

    public $search = '';
    public $permissionSearch = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPermissionSearch()
    {
        $this->resetPage('permissionsPage');
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
        try {
            DB::beginTransaction();
            if ($role->name === 'superadmin') {
                throw new \Exception('Cannot delete Super Admin role!');
            }
            $role->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->handleException('delete role', $e->getMessage());
        }
    }

    public function deletePermission($permissionId)
    {
        try {
            $permission = Permission::findOrFail($permissionId);
            $permissionName = $permission->name;
            $permission->delete();

            Toaster::success("Permission '{$permissionName}' deleted successfully!");
            $this->dispatch('refreshData');
        } catch (\Exception $e) {
            Toaster::error('Failed to delete permission: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $roles = Role::withCount('users')
            ->with('permissions')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate(10, ['*'], 'rolesPage');

        $permissions = Permission::when($this->permissionSearch, function ($query) {
                $query->where('name', 'like', '%' . $this->permissionSearch . '%');
            })
            ->orderBy('guard_name')
            ->orderBy('name')
            ->paginate(15, ['*'], 'permissionsPage');

        // Group permissions by guard
        $groupedPermissions = Permission::orderBy('guard_name')
            ->orderBy('name')
            ->get()
            ->groupBy('guard_name');

        // Get permission guards for stats
        $permissionGuards = Permission::select('guard_name')
            ->distinct()
            ->pluck('guard_name');

        return view('livewire.pages.role-permission.index', [
            'roles' => $roles,
            'permissions' => $permissions,
            'groupedPermissions' => $groupedPermissions,
            'permissionGuards' => $permissionGuards,
        ]);
    }
}
