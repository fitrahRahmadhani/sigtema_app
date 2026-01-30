<?php

namespace App\Livewire\Settings;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Title;
use Masmerise\Toaster\Toaster;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

#[Title('Profile')]
class Profile extends Component
{
    public string $name = '';

    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}\s]+$/u' // Hanya huruf dan spasi
            ],
        ]);

        $user->name = $validated['name'];
        $user->save();

        Toaster::success('Profile updated successfully.');
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));
            return;
        }

        $user->sendEmailVerificationNotification();

        Toaster::success('Verification link sent successfully.');
    }
}
