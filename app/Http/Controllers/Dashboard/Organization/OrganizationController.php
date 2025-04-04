<?php

namespace App\Http\Controllers\Dashboard\Organization;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\LocationOrganization;
use App\Models\Organization;
use App\Models\OrganizationUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrganizationController extends Controller
{
    public function store_organization_details(Request $request)
    {
        $validated = $request->validate([
            'org_name' => 'required',
            'org_category' => 'required',
            'org_phone_no' => 'required',
            'org_address' => 'required|string|max:255',
            'org_location' => 'required|string|max:255',
            'org_postal_code' => 'required|string|max:10',
            'org_district' => 'required',
            'org_province' => 'required',
            'org_br' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $location = Location::create([
            'district_id' => $validated['org_district'],
            'province_id' => $validated['org_province'],
            'name' => $validated['org_location'],
            'address' => $validated['org_address'],
            'postal_code' => $validated['org_postal_code'],
            'phone_number' => $validated['org_phone_no'],
        ]);

        if ($request->file('org_br')) {
            // example path=> BusinessRegistrations/Service Center/Kamburupitiya/Motora/filename.jpg
            $path = $request->file('org_br')->store('BusinessRegistrations/' . $validated['org_category'] . '/' . $validated['org_location'] . '/' . $validated['org_name'], 'private');
        } else {
            $path = null;
        }

        $authUser = Auth::guard('organization_user')->user();

        if ($authUser->isDepartmentOfMotorTraffic() || $authUser->isDivisionalSecretariat() || $authUser->isEmissionTestCenter()) {
            $organization = Organization::find($validated['org_name']);
            $organization->update([
                'main_location_id' => $location->id,
                'br_path' => $path,
            ]);
        }else {
            $organization = Organization::create([
                'org_cat_id' => $authUser->org_cat_id,
                'name' => $validated['org_name'],
                'main_location_id' => $location->id,
                'br_path' => $path,
            ]);
        }

        $locationOrganization = LocationOrganization::create([
            'org_id' => $organization->id,
            'location_id' => $location->id,
        ]);

        $organizationUser = OrganizationUser::find(Auth::guard('organization_user')->user()->id);
        $organizationUser->update([
            'loc_org_id' => $locationOrganization->id,
        ]);

        session()->flash('success', 'Organization Details Saved Successfully.');
        return redirect()->route('dashboard');
    }

    public function update_organization_details(Request $request, $id)
    {
        $validated = $request->validate([
            'org_name' => 'required',
            'org_category' => 'required',
            'org_phone_no' => 'required',
            'org_address' => 'required|string|max:255',
            'org_location' => 'required|string|max:255',
            'org_postal_code' => 'required|string|max:10',
            'org_district' => 'required',
            'org_province' => 'required',
            'org_br' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $organizationUser = OrganizationUser::findOrFail(Auth::guard('organization_user')->user()->id);

        // Fetch existing location and organization details
        $locationOrganization = LocationOrganization::findOrFail($organizationUser->loc_org_id);
        $organization = Organization::findOrFail($locationOrganization->org_id);
        $location = Location::findOrFail($locationOrganization->location_id);

        // ✅ Update Location Details
        $location->update([
            'district_id' => $validated['org_district'],
            'province_id' => $validated['org_province'],
            'name' => $validated['org_location'],
            'address' => $validated['org_address'],
            'postal_code' => $validated['org_postal_code'],
            'phone_number' => $validated['org_phone_no'],
        ]);

        // ✅ Handle Business Registration File Upload (BR)
        if ($request->hasFile('org_br')) {
            // Delete the old file if it exists
            if ($organization->br_path && Storage::disk('private')->exists($organization->br_path)) {
                Storage::disk('private')->delete($organization->br_path);
            }

            // Store new file
            $path = $request->file('org_br')->store('BusinessRegistrations/' . $validated['org_category'] . '/' . $validated['org_location'] . '/' . $validated['org_name'], 'private');
        } else {
            $path = $organization->br_path; // Keep the existing file if not updated
        }

        if(!(Auth::guard('organization_user')->user()->isDepartmentOfMotorTraffic()) && !(Auth::guard('organization_user')->user()->isDivisionalSecretariat()) && !(Auth::guard('organization_user')->user()->isEmissionTestCenter())) {
            // ✅ Update Organization Details
            $organization->update([
                'name' => $validated['org_name'],
                'main_location_id' => $location->id,
                'br_path' => $path,
            ]);
        }else{
            $organization->update([
                'main_location_id' => $location->id,
                'br_path' => $path,
            ]);
        }

        // ✅ Success Message & Redirect
        session()->flash('success', 'Organization Details Updated Successfully.');
        return redirect()->route('dashboard');
    }
}
