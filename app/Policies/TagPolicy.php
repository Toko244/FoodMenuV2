<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\Tag;
use App\Models\User;

class TagPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        $company = Company::where('id', $user->current_company_id)->first();

        if (!$company) {
            return false;
        }

        if ($user->hasRole('Admin') || ($user->hasAnyPermission(['company-manage', 'company-manage_own']))) {
            return true;
        }

        if ($company->ambassadors()->where('user_id', $user->id)->wherePivot('can_edit', true)->exists()) {
            return true;
        }

        if ($company->users()->where('user_id', $user->id)->exists()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        $company = Company::where('id', $user->current_company_id)->first();

        if (!$company) {
            return false;
        }

        if ($user->hasRole('Admin') || ($user->hasAnyPermission(['company-manage', 'company-manage_own']))) {
            return true;
        }

        if ($company->users()->where('user_id', $user->id)->exists()) {
            return true;
        }

        if ($company->ambassadors()->where('user_id', $user->id)->wherePivot('can_edit', true)->exists()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        $company = Company::where('id', $user->current_company_id)->first();

        if (!$company) {
            return false;
        }

        if ($user->hasRole('Admin') || ($user->hasAnyPermission(['company-manage', 'company-manage_own']))) {
            return true;
        }

        if ($company->ambassadors()->where('user_id', $user->id)->wherePivot('can_edit', true)->exists()) {
            return true;
        }

        if ($company->users()->where('user_id', $user->id)->exists()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        $company = Company::where('id', $user->current_company_id)->first();

        if (!$company) {
            return false;
        }

        if ($user->hasRole('Admin') || ($user->hasAnyPermission(['company-manage', 'company-manage_own']))) {
            return true;
        }

        if ($company->ambassadors()->where('user_id', $user->id)->wherePivot('can_edit', true)->exists()) {
            return true;
        }

        if ($company->users()->where('user_id', $user->id)->exists()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        $company = Company::where('id', $user->current_company_id)->first();

        if (!$company) {
            return false;
        }

        if ($user->hasRole('Admin') || ($user->hasAnyPermission(['company-manage', 'company-manage_own']))) {
            return true;
        }

        if ($company->ambassadors()->where('user_id', $user->id)->wherePivot('can_edit', true)->exists()) {
            return true;
        }

        if ($company->users()->where('user_id', $user->id)->exists()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Tag $tag): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Tag $tag): bool
    {
        //
    }
}
