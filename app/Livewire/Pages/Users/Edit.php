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
#[Title('Edit User')]
class Edit extends Component
{
    use WithFileUploads;

    public User $user;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|email|max:255|unique:users,email,' . '$this->user->id')]
    public $email = '';

    #[Validate([
        'nullable',
        'string',
        'min:12',
        'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'
    ])]
    public $password = '';

    #[Validate('nullable|same:password')]
    public $password_confirmation = '';

    #[Validate('nullable|numeric|min:1|max:5')]
    public $band = '';

    #[Validate('nullable|exists:companies,id')]
    public $company_id = '';

    #[Validate('nullable|array')]
    public $selectedRoles = [];

    #[Validate('nullable|image|max:2048')]
    public $profile_photo;

    public $existing_profile_photo = null;
    public $profile_photo_preview = null;
    public $companies = [];
    public $bandOptions = [];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->band = $user->band ? (string)$user->band : '';
        $this->company_id = $user->company_id;
        $this->selectedRoles = $user->roles->pluck('name')->toArray();
        $this->existing_profile_photo = $user->profile_path;

        // Load companies
        $this->companies = Company::where('is_active', true)->get();

        // Band options
        $this->bandOptions = range(1, 5);
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

    public function deleteExistingPhoto()
    {
        if ($this->existing_profile_photo && Storage::disk('public')->exists($this->existing_profile_photo)) {
            Storage::disk('public')->delete($this->existing_profile_photo);
        }

        $this->user->update(['profile_path' => null]);
        $this->existing_profile_photo = null;

        Toaster::success('Profile photo deleted successfully!');
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->user->id,
            'password' => 'nullable|string|min:12|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
            'password_confirmation' => 'nullable|same:password',
            'band' => 'nullable|numeric|min:1|max:5',
            'company_id' => 'nullable|exists:companies,id',
            'selectedRoles' => 'nullable|array',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        try {
            // Update user basic info
            $userData = [
                'name' => $this->name,
                'email' => $this->email,
                'company_id' => $this->company_id ?: null,
                'band' => $this->band ? (int)$this->band : null,
            ];

            // Update password only if provided
            if ($this->password) {
                $userData['password'] = Hash::make($this->password);
            }

            $this->user->update($userData);

            // Upload new profile photo if exists
            if ($this->profile_photo) {
                // Delete old photo
                if ($this->existing_profile_photo && Storage::disk('public')->exists($this->existing_profile_photo)) {
                    Storage::disk('public')->delete($this->existing_profile_photo);
                }

                // Store new photo
                $path = $this->profile_photo->store('profiles', 'public');
                $this->user->update(['profile_path' => $path]);
            }

            // Sync roles
            if (!empty($this->selectedRoles)) {
                $this->user->syncRoles($this->selectedRoles);
            } else {
                // Remove all roles if none selected
                $this->user->syncRoles([]);
            }

            Toaster::success('User updated successfully!');
            return redirect()->route('users.index');
        } catch (\Exception $e) {
            Toaster::error('Failed to update user: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('users.index');
    }

    public function render()
    {
        $roles = Role::orderBy('name')->get();

        return view('livewire.pages.users.edit', [
            'roles' => $roles,
        ]);
    }
}
