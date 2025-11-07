<?php

namespace App\Livewire\Pages\RolePermission;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Spatie\Permission\Models\Role;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Create Role - Role Management')]
class Create extends Component
{
    public $roleName = '';

    public function saveRole()
    {
        $this->validate([
            'roleName' => 'required|string|max:255|unique:roles,name',
        ]);

        try {
            // Create new role
            Role::create([
                'name' => $this->roleName,
                'guard_name' => 'web'
            ]);

            // Redirect to roles index
            return redirect()->route('role-permissions.index')->success('Role created successfully!');
        } catch (\Exception $e) {
            Toaster::error('Error creating role: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pages.role-permission.create');
    }
}
