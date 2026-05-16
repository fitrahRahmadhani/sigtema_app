<?php

namespace App\Helpers;

use App\Models\User;

class RoleRedirectHelper
{
    /**
     * Get the appropriate home route for a user based on their role.
     */
    public static function getHomeRoute(User $user): string
    {
        if ($user->hasRole('superadmin') || $user->hasRole('admin') || $user->hasRole('hc-operator')) {
            return 'dashboard';
        }

        if ($user->hasRole('head-of-unit')) {
            return 'career-path';
        }

        if ($user->hasRole('employee')) {
            return 'my-profile';
        }

        return 'login';
    }
}