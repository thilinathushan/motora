<?php

namespace App\Http\Controllers\Dashboard\LocationDetails;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\LocationOrganization;
use App\Models\OrganizationUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    public function store_location_details(Request $request)
    {
        $validated = $request->validate([
            'loc_address' => 'required|string|max:255',
            'loc_location' => 'required|string|max:255',
            'loc_postal_code' => 'required|string|max:10',
            'loc_phone_no' => 'required',
            'loc_district' => 'required',
            'loc_province' => 'required',
        ]);

        $location = Location::create([
            'district_id' => $validated['loc_district'],
            'province_id' => $validated['loc_province'],
            'name' => $validated['loc_location'],
            'address' => $validated['loc_address'],
            'postal_code' => $validated['loc_postal_code'],
            'phone_number' => $validated['loc_phone_no'],
        ]);

        $user = Auth::guard('organization_user')->user();
        if (!$user) {
            return redirect()->route('organization.login_view');
        }

        $loc_org_id = OrganizationUser::find($user->id)->loc_org_id;
        $org_id = LocationOrganization::findOrFail($loc_org_id)->org_id;

        LocationOrganization::create([
            'org_id' => $org_id,
            'location_id' => $location->id,
        ]);

        session()->flash('success', 'Location Details Saved Successfully.');
        return redirect()->route('dashboard.manageLocationDetails');
    }

    public function changeLocationStatus($id)
    {
        $location = Location::withTrashed()->findOrFail($id);

        if ($location->trashed()) {
            // If already deleted, restore the location
            $location->restore();

            // Restore the related location organization record
            $locationOrganization = LocationOrganization::withTrashed()->where('location_id', $location->id)->first();
            if ($locationOrganization) {
                $locationOrganization->restore();
            }

            session()->flash('success', 'Location restored successfully.');
        } else {
            // If not deleted, soft delete the location
            $location->delete();

            // Soft delete the related location organization record
            $locationOrganization = LocationOrganization::where('location_id', $location->id)->first();
            if ($locationOrganization) {
                $locationOrganization->delete();
            }

            session()->flash('success', 'Location deleted successfully.');
        }

        return redirect()->back();
    }
}
