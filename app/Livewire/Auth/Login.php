<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Features;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class Login extends Component
{
    #[Title('Login')]

    #[Validate('required', message: "Kolom email tidak boleh kosong")]
    #[Validate('email', message: "Format email salah")]
    public string $email = '';

    #[Validate('required', message: "Kolom password tidak boleh kosong")]
    public string $password = '';

    #[Validate('required', message: 'Verifikasi bahwa Anda bukan robot')]
    public string $gRecaptchaResponse = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->email = is_array($this->email) ? '' : trim((string) $this->email);
        $this->password = is_array($this->password) ? '' : trim((string) $this->password);
        $this->gRecaptchaResponse = is_array($this->gRecaptchaResponse) ? '' : trim((string) $this->gRecaptchaResponse);

        $this->validate();

        $this->ensureIsNotRateLimited();

        $user = $this->validateCredentials();

        if (Features::canManageTwoFactorAuthentication() && $user->hasEnabledTwoFactorAuthentication()) {
            Session::put([
                'login.id'       => $user->getKey(),
                'login.remember' => $this->remember,
            ]);

            $this->redirect(route('two-factor.login'), navigate: true);
            return;
        }

        Auth::login($user, $this->remember);
        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        /** @var User $authUser */
        $authUser = Auth::user();

        Log::info('User logged in', [
            'idUser' => $authUser->id,
            'user'   => $authUser->name,
            'email'  => $authUser->email,
            'role'   => $authUser->roles->first()->name,
        ]);

        activity()->event('Login')->withProperties(['actor' => $authUser->name, 'role' => $authUser->roles->first()->name])->log('Actor logged in');

        if ($authUser->hasRole('superadmin') || $authUser->hasRole('admin') || $authUser->hasRole('hc-operator')) {
            $this->redirect(route('dashboard'), navigate: true);
        } elseif ($authUser->hasRole('head-of-unit')) {
            $this->redirect(route('career-path'), navigate: true);
        } elseif ($authUser->hasRole('employee')) {
            $this->redirect(route('my-profile'), navigate: true);
        } else {
            abort(403);
        }
    }

    /**
     * Validate the user's credentials.
     */
    protected function validateCredentials(): User
    {
        $user = Auth::getProvider()->retrieveByCredentials(['email' => $this->email, 'password' => $this->password]);

        if (! $user) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'Email tidak terdaftar dalam sistem kami.',
            ]);
        }

        if (! Auth::getProvider()->validateCredentials($user, ['password' => $this->password])) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'password' => 'Password yang Anda masukkan salah.',
            ]);
        }

        return $user;
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());
        $minutes = ceil($seconds / 60);

        throw ValidationException::withMessages([
            'email' => "Terlalu banyak percobaan login gagal. Silakan coba lagi dalam {$minutes} menit.",
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }
}
