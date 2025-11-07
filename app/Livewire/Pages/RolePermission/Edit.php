<?php

namespace App\Livewire\Pages\RolePermission;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Spatie\Permission\Models\Role;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Edit Role - Role Management')]
class Edit extends Component
{
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

    public function saveRole()
    {
        $this->validate([
            'roleName' => 'required|string|max:255|unique:roles,name,' . $this->roleId,
        ]);

        try {
            // Update role
            $this->role->update([
                'name' => $this->roleName
            ]);

            // Redirect to roles index
            return redirect()->route('role-permissions.index')->success('Role updated successfully!');
        } catch (\Exception $e) {
            Toaster::error('Error updating role: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pages.role-permission.edit');
    }
}
