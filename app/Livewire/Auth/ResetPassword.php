<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Masmerise\Toaster\Toaster;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

#[Layout('components.layouts.auth')]
#[Title('Reset Password')]
class ResetPassword extends Component
{
    #[Locked]
    public string $token = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email')->value();
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword()
    {
        // Validasi kustom untuk password kuat
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => [
                'required',
                'string',
                'confirmed',
                'min:12',
                'regex:/[a-z]/',      // minimal satu huruf kecil
                'regex:/[A-Z]/',      // minimal satu huruf besar
                'regex:/[0-9]/',      // minimal satu angka
                'regex:/[@$!%*#?&]/', // minimal satu simbol
            ],
        ], [
            'password.min' => 'Password minimal 12 karakter.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan simbol.',
        ]);

        // Reset password
        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // Handle response
        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));
            Toaster::error(__('Gagal mereset password. ' . __($status)));
            return;
        }
        return redirect()->route('login')->success('Password berhasil direset! Silakan login dengan password baru Anda.');
    }

    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}
