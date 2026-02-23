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

            // Redirect to roles index
            return redirect()->route('role-permissions.index')->success('Role updated successfully!');
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

    public function render()
    {
        return view('livewire.pages.role-permission.edit');
    }
}
