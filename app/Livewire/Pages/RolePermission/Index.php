<?php

namespace App\Livewire\Pages\RolePermission;

use App\Livewire\Traits\HandleException;
use App\Livewire\Traits\WithDeleteConfirmation;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Role;

#[Layout('components.layouts.app')]
#[Title('Role Management')]
class Index extends Component
{
    use WithPagination, WithDeleteConfirmation, HandleException;

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
        try {
            DB::beginTransaction();
            // Prevent deletion of super admin role
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
