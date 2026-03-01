<?php

namespace App\Livewire\Pages\Users;

use App\Livewire\Traits\HandleException;
use App\Livewire\Traits\WithDeleteConfirmation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Users')]
class Index extends Component
{
    use WithPagination, WithDeleteConfirmation, HandleException;

    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'sort')]
    public $sortBy = 'name_asc';

    #[Url(as: 'role')]
    public $roleFilter = '';

    #[Url(as: 'company')]
    public $companyFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function updatingCompanyFilter()
    {
        $this->resetPage();
    }

    public function edit($userId)
    {
        $user = User::findOrFail($userId);
        return $this->redirect(route('users.edit', $user->id), navigate: true);
    }

    // Implement abstract methods from trait
    protected function getModelClass(): string
    {
        return User::class;
    }

    protected function getModelDisplayName(): string
    {
        return 'User';
    }

    // Override performDelete untuk custom logic
    protected function performDelete($user): void
    {
        $this->authorize('delete', User::class);

        try {
            DB::beginTransaction();
            $profilePath = $user->profile_path;

            // Delete the user from database
            $user->delete();

            // Delete profile picture jika ada
            if ($profilePath && Storage::disk('public')->exists($profilePath)) {
                Storage::disk('public')->delete($profilePath);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->handleException('delete user and profile picture', $e->getMessage());
        }
    }

    public function render()
    {
        $users = User::query()
            ->with(['company', 'roles'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhereHas('company', function ($companyQuery) {
                            $companyQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->roleFilter, function ($query) {
                $query->role($this->roleFilter);
            })
            ->when($this->companyFilter, function ($query) {
                $query->where('company_id', $this->companyFilter);
            })
            ->when($this->sortBy === 'name_asc', fn($query) => $query->orderBy('name', 'asc'))
            ->when($this->sortBy === 'name_desc', fn($query) => $query->orderBy('name', 'desc'))
            ->when($this->sortBy === 'newest', fn($query) => $query->latest('created_at'))
            ->when($this->sortBy === 'oldest', fn($query) => $query->oldest('created_at'))
            ->paginate(10);

        // Get all roles for filter
        $roles = \Spatie\Permission\Models\Role::orderBy('name')->get();

        // Get all companies for filter
        $companies = \App\Models\Company::orderBy('name')->get();

        return view('livewire.pages.users.index', [
            'users' => $users,
            'roles' => $roles,
            'companies' => $companies,
        ]);
    }
}
