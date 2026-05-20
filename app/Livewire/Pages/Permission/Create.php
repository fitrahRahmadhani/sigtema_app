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
#[Title('Create Permission - Permission Management')]
class Create extends Component
{
    use UnauthorizedAccess, HandleException;

    public $permissionName = '';
    public $guardName = 'web';

    public function rules()
    {
        return [
            'permissionName' => 'required|string|max:255|unique:permissions,name',
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
            $this->authorize('create', Permission::class);

            DB::beginTransaction();

            Permission::create([
                'name' => $this->permissionName,
                'guard_name' => $this->guardName,
            ]);

            DB::commit();

            return redirect()->route('role-permissions.index')->success('Permission created successfully!');
        } catch (ValidationException $e) {
            DB::rollBack();
            Toaster::error('Failed to create permission: ' . $e->getMessage());
            throw $e;
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return $this->unauthorizedAccess('create permission');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException('create permission', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pages.permission.create');
    }
}
