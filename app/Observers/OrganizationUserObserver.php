<?php

namespace App\Observers;

use App\Models\LocationOrganization;
use App\Models\OrganizationUser;

class OrganizationUserObserver
{
    /**
     * Handle the OrganizationUser "created" event.
     */
    public function created(OrganizationUser $organizationUser): void
    {
        //
    }

    /**
     * Handle the OrganizationUser "updated" event.
     */
    public function updated(OrganizationUser $organizationUser): void
    {
        //
    }

    /**
     * Handle the OrganizationUser "deleted" event.
     */
    public function deleted(OrganizationUser $organizationUser): void
    {
        //
    }

    /**
     * Handle the OrganizationUser "restored" event.
     */
    public function restored(OrganizationUser $organizationUser): void
    {
        //
    }

    /**
     * Handle the OrganizationUser "force deleted" event.
     */
    public function forceDeleted(OrganizationUser $organizationUser): void
    {
        //
    }

    /**
     * Handle the OrganizationUser "saving" event.
     * This runs before a user is created or updated.
     */
    public function saving(OrganizationUser $user): void
    {
        // --- FORWARD SYNC ---
        // If the old column is present or changed, update the new ones.
        // This keeps the existing code working.
        if ($user->isDirty('loc_org_id') && !empty($user->loc_org_id)) {
            $locationOrg = LocationOrganization::find($user->loc_org_id);
            if ($locationOrg) {
                $user->organization_id = $locationOrg->org_id;
                $user->location_id = $locationOrg->location_id;
            }
        }

        // --- BACKWARD SYNC ---
        // If the new columns are changed, update the old one.
        // This allows the new code to work without breaking old features.
        if (($user->isDirty('organization_id') || $user->isDirty('location_id')) &&
            !empty($user->organization_id) && !empty($user->location_id))
        {
            $locationOrg = LocationOrganization::where('org_id', $user->organization_id)
                                               ->where('location_id', $user->location_id)
                                               ->first();
            if ($locationOrg) {
                $user->loc_org_id = $locationOrg->id;
            }
        }
    }
}
