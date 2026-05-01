<?php

namespace App\Livewire\Settings;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;
use App\Livewire\Traits\HandleException;
use App\Livewire\Traits\UnauthorizedAccess;

#[Title('Profile Settings')]
class Profile extends Component
{
    use WithFileUploads, UnauthorizedAccess, HandleException;

    public $name = '';
    public $current_password = '';
    public $password = '';
    public $password_confirmation = '';
    public $profile_photo = null;
    public $existing_profile_photo = null;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->existing_profile_photo = $user->profile_path;
    }

    public function rules()
    {
        $passwordRules = [
            'required_with:password',
            'current_password'
        ];

        // Jika password diisi, wajib current_password
        if ($this->password || $this->password_confirmation) {
            $passwordRules = array_merge($passwordRules, ['required']);
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            'current_password' => $passwordRules,
            'password' => [
                'nullable',
                'string',
                'min:12',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
                'confirmed'
            ],
            'password_confirmation' => ['nullable', 'same:password'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'current_password.required' => 'Password saat ini wajib diisi untuk mengganti password.',
            'current_password.current_password' => 'Password saat ini tidak valid.',
            'password.min' => 'Password baru minimal 12 karakter.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan simbol (@$!%*#?&).',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'profile_photo.image' => 'File harus berupa gambar (JPG, PNG, etc).',
            'profile_photo.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'password' || $propertyName === 'password_confirmation') {
            // Jika password diisi, validate current_password
            if ($this->password || $this->password_confirmation) {
                $this->validateOnly('current_password');
            }
        }

        if ($propertyName === 'profile_photo') {
            $this->validateOnly('profile_photo');
        }
    }

    public function updatedProfilePhoto()
    {
        try {
            $this->validate([
                'profile_photo' => ['nullable', 'image', 'max:2048'],
            ]);
        } catch (ValidationException $e) {
            Toaster::error('Validation failed: ' . $e->getMessage());
            $this->profile_photo = null;
            throw $e;
        }
    }

    public function deleteExistingPhoto()
    {
        $user = Auth::user();

        try {
            // Authorization check
            $this->authorize('update', $user);

            DB::beginTransaction();

            $oldPhotoPath = $user->profile_path;

            // Update database first
            $user->profile_path = null;
            $user->save();

            // Delete file after successful database update
            if ($oldPhotoPath && Storage::disk('public')->exists($oldPhotoPath)) {
                Storage::disk('public')->delete($oldPhotoPath);
            }

            $this->existing_profile_photo = null;

            DB::commit();

            Toaster::success('Photo profile deleted successfully!');
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return $this->unauthorizedAccess('delete profile photo');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException('delete profile photo', $e->getMessage());
        }
    }

    public function save()
    {
        try {
            // Validate form
            $this->validate();

            $user = Auth::user();

            // Authorization check
            $this->authorize('update', $user);

            DB::beginTransaction();

            $oldPhotoPath = $user->profile_path;

            // Update name
            $user->name = $this->name;

            // Update password if provided
            if ($this->password) {
                $user->password = Hash::make($this->password);
            }

            // Upload profile photo if exists
            if ($this->profile_photo) {
                // Store new photo first
                $newPath = $this->profile_photo->store('profiles', 'public');
                $user->profile_path = $newPath;
            }

            // Save user data
            $user->save();

            // Delete old photo after successful database update (only if new photo uploaded)
            if ($this->profile_photo && $oldPhotoPath && Storage::disk('public')->exists($oldPhotoPath)) {
                Storage::disk('public')->delete($oldPhotoPath);
            }

            DB::commit();

            return redirect()->route('settings.profile')->success('Profile updated successfully!');
        } catch (ValidationException $e) {
            DB::rollBack();
            Toaster::error('Validation failed: ' . $e->getMessage());
            throw $e;
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return $this->unauthorizedAccess('update profile');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException('update profile', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.settings.profile');
    }
}
