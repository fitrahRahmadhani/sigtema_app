<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Password;
use Masmerise\Toaster\Toaster;

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

        $status = Password::sendResetLink($this->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            Toaster::success(__('Tautan reset password telah dikirim ke email Anda.'));

            // Reset form
            $this->reset(['email', 'gRecaptchaResponse']);

            // Reset reCAPTCHA
            $this->dispatch('resetRecaptcha');
        } else {
            Toaster::error(__('Gagal mengirim tautan reset. Silakan coba lagi.'));
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
