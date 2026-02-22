<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\PsychogramAspect;
use App\Models\User;

class PsychogramAspectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PsychogramAspect $psychogramAspect): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Company $company = null): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }
        return $user->hasRole('admin') && $user->company_id === $company->id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PsychogramAspect $psychogramAspect): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        return $user->hasRole('admin') && $user->company_id === $psychogramAspect->company->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PsychogramAspect $psychogramAspect): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }
        return $user->hasRole('admin') && $user->company_id === $psychogramAspect->company->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PsychogramAspect $psychogramAspect): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PsychogramAspect $psychogramAspect): bool
    {
        return false;
    }
}
