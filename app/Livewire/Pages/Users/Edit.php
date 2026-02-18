<?php

namespace App\Livewire\Pages\Users;

use App\Livewire\Traits\HandleException;
use App\Livewire\Traits\UnauthorizedAccess;
use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;
use Spatie\Permission\Models\Role;

#[Layout('components.layouts.app')]
#[Title('Edit User')]
class Edit extends Component
{
    use WithFileUploads, UnauthorizedAccess, HandleException;

    public User $user;

    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $band = '';
    public $company_id = '';
    public $selectedRoles = [];
    public $profile_photo;

    public $existing_profile_photo = null;
    public $profile_photo_preview = null;
    public $companies = [];
    public $bandOptions = [];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->user->id,
            'password' => [
                'nullable',
                'string',
                'min:12',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'
            ],
            'password_confirmation' => 'nullable|same:password',
            'band' => 'nullable|numeric|min:1|max:5',
            'company_id' => 'nullable|exists:companies,id',
            'selectedRoles' => 'nullable|array',
            'profile_photo' => 'nullable|image|max:2048',
        ];
    }

    protected function messages()
    {
        return [
            'email.unique' => 'Email ini sudah digunakan oleh user lain.',
            'password.min' => 'Password minimal 12 karakter.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter khusus.',
            'password_confirmation.same' => 'Konfirmasi password tidak cocok.',
            'profile_photo.image' => 'File harus berupa gambar.',
            'profile_photo.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }

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
        $this->validateOnly('profile_photo');

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
        try {
            // Validasi form
            $this->validate();

            // Authorization check
            $this->authorize('update', User::class);

            DB::beginTransaction();

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

            DB::commit();

            return redirect()->route('users.index')->success('User updated successfully!');
        } catch (ValidationException $e) {
            DB::rollBack();
            Toaster::error('Failed to create user: ' . $e->getMessage());
            throw $e;
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return $this->unauthorizedAccess('edit user');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException('edit user', $e->getMessage());
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
