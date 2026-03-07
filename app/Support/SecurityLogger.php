<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SecurityLogger
{
    public static function forbidden(Request $request, string $reason = ''): void
    {
        if (app()->runningInConsole()) {
            return;
        }

        $user = $request->user();

        Log::warning('403 Forbidden — Unauthorized access attempt', [
            'timestamp'   => now()->toDateTimeString(),
            'incident_id' => 'INC-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -6)),
            'user_id'     => $user?->id ?? 'unauthenticated',
            'user_name'   => $user?->name ?? '-',
            'user_email'  => $user?->email ?? '-',
            'user_role'   => $user?->roles?->first()?->name ?? '-',
            'ip_address'  => $request->ip(),
            'url'         => $request->fullUrl(),
            'method'      => $request->method(),
            'user_agent'  => $request->userAgent(),
            'referer'     => $request->header('referer', '-'),
            'reason'      => $reason ?: 'Access denied',
        ]);
    }
}
