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
use Illuminate\Validation\Rule;
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

    #[Validate('nullable|exists:companies,id')]
    public $company_id = '';

    #[Validate('nullable|array')]
    public $selectedRoles = [];

    #[Validate('nullable|image|max:2048')]
    public $profile_photo;

    public $existing_profile_photo = null;
    public $profile_photo_preview = null;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->company_id = $user->company_id;
        $this->selectedRoles = $user->roles->pluck('name')->toArray();
        $this->existing_profile_photo = $user->profile_path;
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
        $this->validate();

        try {
            // Update user basic info
            $userData = [
                'name' => $this->name,
                'email' => $this->email,
                'company_id' => $this->company_id ?: null,
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

            return redirect()->route('users.index')->success('User updated successfully!');
        } catch (\Exception $e) {
            Toaster::error('Failed to update user: ' . $e->getMessage());
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

        return view('livewire.pages.users.edit', [
            'companies' => $companies,
            'roles' => $roles,
        ]);
    }
}
