<?php

namespace App\Livewire\Pages\Permission;

use App\Livewire\Traits\HandleException;
use App\Livewire\Traits\UnauthorizedAccess;
use App\Models\Permission;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Edit Permission - Permission Management')]
class Edit extends Component
{
    use UnauthorizedAccess, HandleException;

    public $permissionId;
    public $permissionName = '';
    public $guardName = '';
    public $permission;

    public function mount($id)
    {
        $this->permissionId = $id;
        $this->loadPermission();
    }

    public function loadPermission()
    {
        $this->permission = Permission::findOrFail($this->permissionId);
        $this->permissionName = $this->permission->name;
        $this->guardName = $this->permission->guard_name;
    }

    public function rules()
    {
        return [
            'permissionName' => 'required|string|max:255|unique:permissions,name,' . $this->permissionId,
            'guardName' => 'required|string|in:web,api',
        ];
    }

    public function messages()
    {
        return [
            'permissionName.required' => 'Permission name is required.',
            'permissionName.unique' => 'This permission already exists.',
            'permissionName.max' => 'Permission name must not exceed 255 characters.',
            'permissionName.string' => 'Permission name must be a string.',
            'guardName.required' => 'Guard is required.',
            'guardName.in' => 'Guard must be either web or api.',
        ];
    }

    public function savePermission()
    {
        try {
            $this->validate();
            $this->authorize('update', $this->permission);

            DB::beginTransaction();

            $this->permission->update([
                'name' => $this->permissionName,
            ]);

            DB::commit();

            return redirect()->route('role-permissions.index')->success('Permission updated successfully!');
        } catch (ValidationException $e) {
            DB::rollBack();
            Toaster::error('Failed to edit permission: ' . $e->getMessage());
            throw $e;
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return $this->unauthorizedAccess('edit permission');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException('edit permission', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pages.permission.edit');
    }
}
