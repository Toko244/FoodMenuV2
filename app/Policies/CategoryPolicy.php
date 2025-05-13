<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\Company;
use App\Models\User;

class CategoryPolicy
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

        if ($company->ambassadors()->where('user_id', $user->id)->wherePivot('can_edit', true)->exists()) {
            return true;
        }

        if ($company->users()->where('user_id', $user->id)->exists()) {
            return true;
        }

        if ($user->hasRole('Admin') || ($user->hasAnyPermission(['company-manage', 'company-manage_own']))) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Category $category): bool
    {
        $company = Company::where('id', $category->company_id)->first();

        if (!$company) {
            return false;
        }

        if ($company->users()->where('user_id', $user->id)->exists()) {
            return true;
        }

        if ($company->ambassadors()->where('user_id', $user->id)->wherePivot('can_edit', true)->exists()) {
            return true;
        }

        if ($user->hasRole('Admin') || ($user->hasAnyPermission(['company-manage', 'company-manage_own']))) {
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

        if ($company->ambassadors()->where('user_id', $user->id)->wherePivot('can_edit', true)->exists()) {
            return true;
        }

        if ($company->users()->where('user_id', $user->id)->exists()) {
            return true;
        }

        if ($user->hasRole('Admin') || ($user->hasAnyPermission(['company-manage', 'company-manage_own']))) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Category $category): bool
    {
        $company = Company::where('id', $category->company_id)->first();

        if (!$company) {
            return false;
        }

        if ($company->users()->where('user_id', $user->id)->exists()) {
            return true;
        }

        if ($company->ambassadors()->where('user_id', $user->id)->wherePivot('can_edit', true)->exists()) {
            return true;
        }

        if ($user->hasRole('Admin') || ($user->hasAnyPermission(['company-manage', 'company-manage_own']))) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Category $category): bool
    {
        $company = Company::where('id', $category->company_id)->first();

        if (!$company) {
            return false;
        }

        if ($company->users()->where('user_id', $user->id)->exists()) {
            return true;
        }

        if ($company->ambassadors()->where('user_id', $user->id)->wherePivot('can_edit', true)->exists()) {
            return true;
        }

        if ($user->hasRole('Admin') || ($user->hasAnyPermission(['company-manage', 'company-manage_own']))) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Category $category): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Category $category): bool
    {
        //
    }
}
