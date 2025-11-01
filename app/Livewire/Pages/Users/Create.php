<?php

namespace App\Livewire\Pages\Users;

use App\Models\User;
use App\Models\Company;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Create User')]
class Create extends Component
{
    use WithFileUploads;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|email|max:255|unique:users,email')]
    public $email = '';

    #[Validate('required|string|min:8')]
    public $password = '';

    #[Validate('required|same:password')]
    public $password_confirmation = '';

    #[Validate('nullable|exists:companies,id')]
    public $company_id = '';

    #[Validate('nullable|array')]
    public $selectedRoles = [];

    #[Validate('nullable|image|max:2048')]
    public $profile_photo;

    public $profile_photo_preview = null;

    public function mount()
    {
        // Set default values if needed
    }

    public function updatedProfilePhoto()
    {
        $this->validate([
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        if ($this->profile_photo) {
            $this->profile_photo_preview = $this->profile_photo->temporaryUrl();
        }
    }

    public function removeProfilePhoto()
    {
        $this->profile_photo = null;
        $this->profile_photo_preview = null;
    }

    public function save()
    {
        $this->validate();

        try {
            // Create user
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'company_id' => $this->company_id ?: null,
            ]);

            // Upload profile photo if exists
            if ($this->profile_photo) {
                $path = $this->profile_photo->store('profiles', 'public');
                $user->update(['profile_path' => $path]);
            }

            // Assign roles if selected
            if (!empty($this->selectedRoles)) {
                $user->syncRoles($this->selectedRoles);
            }

            Toaster::success('User created successfully!');

            return $this->redirect(route('users.index'), navigate: true);
        } catch (\Exception $e) {
            Toaster::error('Failed to create user: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return $this->redirect(route('users.index'), navigate: true);
    }

    public function render()
    {
        $companies = Company::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();

        return view('livewire.pages.users.create', [
            'companies' => $companies,
            'roles' => $roles,
        ]);
    }
}
