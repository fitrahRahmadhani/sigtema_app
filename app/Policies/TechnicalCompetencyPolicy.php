<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\TechnicalCompetency;
use App\Models\User;

class TechnicalCompetencyPolicy
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
    public function view(User $user, TechnicalCompetency $technicalCompetency): bool
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
    public function update(User $user, TechnicalCompetency $technicalCompetency): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }
        return $user->hasRole('admin') && $user->company_id === $technicalCompetency->employee->company_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TechnicalCompetency $technicalCompetency): bool
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }

        return $user->hasRole('admin') && $user->company_id === $technicalCompetency->employee->company_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TechnicalCompetency $technicalCompetency): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TechnicalCompetency $technicalCompetency): bool
    {
        return false;
    }
}
