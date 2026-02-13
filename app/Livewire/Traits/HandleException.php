<?php

namespace App\Livewire\Traits;

use Illuminate\Support\Str;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Facades\Auth;

trait HandleException
{
    /**
     * Handle exception by logging out the user
     */
    protected function handleException(string $action = 'perform this action', string $message = '')
    {
        $logId = $this->generateErrorId();

        // Log security event
        logger()->error('Exception occurred', [
            'ID' => $logId,
            'user_id' => Auth::id(),
            'user_email' => Auth::user()?->email,
            'action' => $action,
            'message' => $message,
            'timestamp' => now()
        ]);

        Toaster::error('Something went wrong. Please try again. Contact your administrator with the error ID: ' . $logId);
    }

    protected function generateErrorId(): string
    {
        $date = now()->format('Ymd');
        $time = now()->format('His');
        $random = strtoupper(Str::random(5));

        return "ERR-{$date}-{$time}-{$random}";
    }
}
