<?php

namespace App\Livewire\Traits;

use Illuminate\Support\Facades\Auth;

trait UnauthorizedAccess
{
    /**
     * Handle unauthorized access by logging out the user
     */
    protected function unauthorizedAccess(string $action = 'perform this action'): mixed
    {
        // Log security event
        logger()->critical('Unauthorized access attempt - User logged out', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()?->email,
            'action' => $action,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()
        ]);

        // Logout user
        Auth::logout();

        // Invalidate session
        request()->session()->invalidate();
        request()->session()->regenerateToken();;

        // Redirect ke login
        return redirect()->route('login')->error('Your account does not have the necessary permissions. You have been logged out. Please contact your administrator.');
    }
}
