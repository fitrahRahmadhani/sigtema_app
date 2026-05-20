<?php

namespace App\Livewire\Pages\RolePermission;

use App\Livewire\Traits\HandleException;
use App\Livewire\Traits\UnauthorizedAccess;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Masmerise\Toaster\Toaster;
use App\Models\Role;
use Spatie\Permission\Models\Permission;

#[Layout('components.layouts.app')]
#[Title('Edit Role - Role Management')]
class Edit extends Component
{
    use UnauthorizedAccess, HandleException;

    public $roleId;
    public $roleName = '';
    public $roleCount = 0;
    public $role;

    public function mount($id)
    {
        $this->roleId = $id;
        $this->roleCount = Role::where('id',  $this->roleId)->count();
        $this->loadRole();
    }

    public function loadRole()
    {
        $this->role = Role::findOrFail($this->roleId);
        $this->roleName = $this->role->name;
    }

    public function rules()
    {
        return [
            'roleName' => 'required|string|max:255|unique:roles,name,' . $this->roleId,
        ];
    }

    public function messages()
    {
        return [
            'roleName.required' => 'Role name is required.',
            'roleName.unique' => 'This role already exists.',
            'roleName.max' => 'Role name must not exceed 255 characters.',
            'roleName.string' => 'Role name must be a string.',
        ];
    }

    public function saveRole()
    {
        try {
            $this->validate();
            $this->authorize('update', $this->role);

            DB::beginTransaction();

            $this->role->update([
                'name' => $this->roleName
            ]);

            DB::commit();

            Toaster::success('Role updated successfully!');
            return redirect()->route('role-permissions.index');
        } catch (ValidationException $e) {
            DB::rollBack();
            Toaster::error('Failed to edit role: ' . $e->getMessage());
            throw $e;
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return $this->unauthorizedAccess('edit role');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException('edit role', $e->getMessage());
        }
    }

    public function togglePermission($permissionId)
    {
        try {
            $permission = Permission::findOrFail($permissionId);

            if ($this->role->hasPermissionTo($permission)) {
                $this->role->revokePermissionTo($permission);
                Toaster::info("Permission '{$permission->name}' removed from role '{$this->role->name}'");
            } else {
                $this->role->givePermissionTo($permission);
                Toaster::success("Permission '{$permission->name}' added to role '{$this->role->name}'");
            }
        } catch (\Exception $e) {
            Toaster::error('Failed to toggle permission: ' . $e->getMessage());
        }
    }

    public function syncPermissions($permissionIds)
    {
        try {
            $this->role->syncPermissions($permissionIds);
            Toaster::success("Permissions synchronized for role '{$this->role->name}'!");
        } catch (\Exception $e) {
            Toaster::error('Failed to sync permissions: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $this->role = Role::findOrFail($this->roleId);

        // Get all permissions grouped by guard
        $allPermissions = Permission::orderBy('guard_name')
            ->orderBy('name')
            ->get()
            ->groupBy('guard_name');

        return view('livewire.pages.role-permission.edit', [
            'role' => $this->role,
            'roleCount' => $this->roleCount,
            'allPermissions' => $allPermissions,
        ]);
    }
}
