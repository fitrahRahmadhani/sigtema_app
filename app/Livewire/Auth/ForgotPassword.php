<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Title;
use Masmerise\Toaster\Toaster;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Password;

#[Layout('components.layouts.auth')]
#[Title('Forget Password')]
class ForgotPassword extends Component
{
    #[Validate('required', message: "Kolom email tidak boleh kosong")]
    #[Validate('email', message: "Format email salah")]
    public string $email = '';

    #[Validate('required', message: 'Verifikasi bahwa Anda bukan robot')]
    public string $gRecaptchaResponse = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate();

        // Cari user berdasarkan email
        $user = User::where('email', $this->email)->first();

        if (!$user) {
            // Untuk keamanan, tetap tampilkan sukses meski email tidak ditemukan
            Toaster::success(__('Tautan reset password telah dikirim ke email Anda.'));
            $this->reset(['email', 'gRecaptchaResponse']);
            return;
        }

        // Generate token
        $token = Password::createToken($user);

        // Kirim notifikasi custom
        $user->sendPasswordResetNotification($token);

        Toaster::success(__('Tautan reset password telah dikirim ke email Anda.'));
        $this->reset(['email', 'gRecaptchaResponse']);
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
