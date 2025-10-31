<?php

namespace App\Livewire\Pages\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;
use App\Livewire\Traits\WithDeleteConfirmation;

#[Layout('components.layouts.app')]
#[Title('Users')]
class Index extends Component
{
    use WithPagination, WithDeleteConfirmation;

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
        // Delete profile picture jika ada
        if ($user->profile_path && Storage::disk('public')->exists($user->profile_path)) {
            Storage::disk('public')->delete($user->profile_path);
        }

        // Delete the user
        $user->delete();
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
