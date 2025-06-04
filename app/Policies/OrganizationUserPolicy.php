<?php

namespace App\Policies;

use App\Models\OrganizationUser;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrganizationUserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(OrganizationUser $currentUser): bool
    {
        // Any user with a role higher than 'Employee' can see the user list.
        return $currentUser->hasRole('Organization Super Admin')
            || $currentUser->hasRole('Organization Admin')
            || $currentUser->hasRole('Organization Manager');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(OrganizationUser $currentUser, OrganizationUser $userToView): bool
    {
        // Can view if they are in the same organization and have permission to view the list.
        return $currentUser->organization_id === $userToView->organization_id && $this->viewAny($currentUser);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(OrganizationUser $currentUser): bool
    {
        // Only Super Admins and Admins can create new users.
        return $currentUser->hasRole('Organization Super Admin') || $currentUser->hasRole('Organization Admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(OrganizationUser $currentUser, OrganizationUser $userToUpdate): bool
    {
        // Rule 1: Must be in the same organization.
        if ($currentUser->organization_id !== $userToUpdate->organization_id) {
            return false;
        }

        // Rule 2: An Admin can edit anyone in their org.
        if ($currentUser->hasRole('Organization Super Admin')) {
            return true;
        }

        // An Admin can edit anyone except a Super Admin.
        if ($currentUser->hasRole('Organization Admin')) {
            return !$userToUpdate->hasRole('Organization Super Admin');
        }

        // A Manager can ONLY edit an Employee.
        if ($currentUser->hasRole('Organization Manager')) {
            return $userToUpdate->hasRole('Organization Employee');
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(OrganizationUser $currentUser, OrganizationUser $userToUpdate): bool
    {
        // SECURITY GATE: Must be in the same organization.
        if ($currentUser->organization_id !== $userToUpdate->organization_id) {
            return false;
        }

        // A Super Admin can delete anyone (except themselves, perhaps).
        if ($currentUser->hasRole('Organization Super Admin')) {
            return $currentUser->id !== $userToUpdate->id;
        }

        // An Admin can delete Managers and Employees.
        if ($currentUser->hasRole('Organization Admin')) {
            return $userToUpdate->hasRole('Organization Manager') || $userToUpdate->hasRole('Organization Employee');
        }

        // No one else can delete users.
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(OrganizationUser $currentUser, OrganizationUser $userToUpdate): bool
    {
        // SECURITY GATE: Must be in the same organization.
        if ($currentUser->organization_id !== $userToUpdate->organization_id) {
            return false;
        }

        // A Super Admin can delete anyone (except themselves, perhaps).
        if ($currentUser->hasRole('Organization Super Admin')) {
            return $currentUser->id !== $userToUpdate->id;
        }

        // An Admin can delete Managers and Employees.
        if ($currentUser->hasRole('Organization Admin')) {
            return $userToUpdate->hasRole('Organization Manager') || $userToUpdate->hasRole('Organization Employee');
        }

        // No one else can delete users.
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(OrganizationUser $currentUser, OrganizationUser $userToUpdate): bool
    {
        // SECURITY GATE: Must be in the same organization.
        if ($currentUser->organization_id !== $userToUpdate->organization_id) {
            return false;
        }

        // A Super Admin can delete anyone (except themselves, perhaps).
        if ($currentUser->hasRole('Organization Super Admin')) {
            return $currentUser->id !== $userToUpdate->id;
        }

        // An Admin can delete Managers and Employees.
        if ($currentUser->hasRole('Organization Admin')) {
            return $userToUpdate->hasRole('Organization Manager') || $userToUpdate->hasRole('Organization Employee');
        }

        // No one else can delete users.
        return false;
    }
}
