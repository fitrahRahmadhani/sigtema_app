<?php

namespace App\Livewire\Settings;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Masmerise\Toaster\Toaster;

#[Title('Profile Settings')]
class Profile extends Component
{
    use WithFileUploads;

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
            'name' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
            'current_password' => $passwordRules,
            'password' => [
                'nullable',
                'string',
                'min:12',
                'regex:/[a-z]/',      // at least one lowercase letter
                'regex:/[A-Z]/',      // at least one uppercase letter
                'regex:/[0-9]/',      // at least one digit
                'regex:/[@$!%*#?&]/', // at least one special character
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
            'name.regex' => 'Nama hanya boleh mengandung huruf dan spasi.',
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
        $this->validate([
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ]);
    }

    public function deleteExistingPhoto()
    {
        $user = Auth::user();

        if ($this->existing_profile_photo && Storage::disk('public')->exists($this->existing_profile_photo)) {
            Storage::disk('public')->delete($this->existing_profile_photo);
        }

        $user->profile_path = null;
        $user->save();

        $this->existing_profile_photo = null;
        Toaster::success('Foto profil berhasil dihapus.');
    }

    public function save()
    {
        $this->validate();

        try {
            $user = Auth::user();

            // Update name
            $user->name = $this->name;

            // Update password if provided
            if ($this->password) {
                $user->password = Hash::make($this->password);
            }

            // Upload profile photo if exists
            if ($this->profile_photo) {
                // Delete old photo
                if ($this->existing_profile_photo && Storage::disk('public')->exists($this->existing_profile_photo)) {
                    Storage::disk('public')->delete($this->existing_profile_photo);
                }

                // Store new photo
                $path = $this->profile_photo->store('profiles', 'public');
                $user->profile_path = $path;
            }

            $user->save();

            Toaster::success('Profil berhasil diperbarui.');

            // Reset form
            $this->current_password = '';
            $this->password = '';
            $this->password_confirmation = '';
            $this->profile_photo = null;
            $this->existing_profile_photo = $user->profile_path;
        } catch (\Exception $e) {
            Toaster::error('Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.settings.profile');
    }
}
