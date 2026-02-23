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
#[Title('Create Role - Role Management')]
class Create extends Component
{
    use UnauthorizedAccess, HandleException;

    public $roleName = '';

    public function rules()
    {
        return [
            'roleName' => 'required|string|max:255|unique:roles,name',
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
            $this->authorize('create', Role::class);

            DB::beginTransaction();

            Role::create([
                'name' => $this->roleName,
                'guard_name' => 'web'
            ]);

            DB::commit();

            // Redirect to roles index
            return redirect()->route('role-permissions.index')->success('Role created successfully!');
        } catch (ValidationException $e) {
            DB::rollBack();
            Toaster::error('Failed to create role: ' . $e->getMessage());
            throw $e;
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return $this->unauthorizedAccess('create role');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException('create role', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pages.role-permission.create');
    }
}
