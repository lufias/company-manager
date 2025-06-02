<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CompanyPolicy
{
    /**
     * Determine whether the user can view any models.
     * Only authenticated users can view the company list.
     */
    public function viewAny(User $user): bool
    {
        return true; // Any authenticated user can view the company list
    }

    /**
     * Determine whether the user can view the model.
     * Users can view companies they created, or admins can view any company.
     */
    public function view(User $user, Company $company): bool
    {
        return $user->isAdmin() || $company->created_by === $user->id;
    }

    /**
     * Determine whether the user can create models.
     * Any authenticated user can create companies.
     */
    public function create(User $user): bool
    {
        return true; // Any authenticated user can create companies
    }

    /**
     * Determine whether the user can update the model.
     * Users can update companies they created, or admins can update any company.
     */
    public function update(User $user, Company $company): bool
    {
        return $user->isAdmin() || $company->created_by === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     * Users can delete companies they created, or admins can delete any company.
     */
    public function delete(User $user, Company $company): bool
    {
        return $user->isAdmin() || $company->created_by === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     * Only admins can restore deleted companies.
     */
    public function restore(User $user, Company $company): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     * Only admins can permanently delete companies.
     */
    public function forceDelete(User $user, Company $company): bool
    {
        return $user->isAdmin();
    }
}
