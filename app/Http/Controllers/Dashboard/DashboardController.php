<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Location;
use App\Models\LocationOrganization;
use App\Models\Organization;
use App\Models\OrganizationUser;
use App\Models\Province;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    // Display Organization Details
    public function organizationDetails()
    {
        $organization_user = Auth::guard('organization_user')->user();

        if (!$organization_user) {
            return redirect()->route('organization.login_view');
        }

        if ($organization_user->loc_org_id == null) {
            return $this->addOrganizationDetails($organization_user->id);
        }
        return $this->editOrganizationDetails($organization_user->id);
    }

    // Display Add Organization Details Form
    private function addOrganizationDetails($organization_user_id)
    {
        $organization_details_add = true;
        $org_category_details = OrganizationUser::select('organization_categories.name', 'organization_categories.id')->join('organization_categories', 'organization_users.org_cat_id', 'organization_categories.id')->where('organization_users.id', $organization_user_id)->first();
        $org_category = $org_category_details->name;


        $organizations = Organization::select('id', 'name')->where('org_cat_id', $org_category_details->id)->get();

        $districts = District::select('id', 'province_id', 'name')->orderBy('name', 'ASC')->get();
        $provinces = Province::select('id', 'name')->orderBy('name', 'ASC')->get();

        return view('pages.organization.dashboard.organization_details', compact('organization_user_id', 'organizations', 'organization_details_add', 'org_category', 'districts', 'provinces'));
    }

    // Display Edit Organization Details Form
    private function editOrganizationDetails($organization_user_id)
    {
        $organization_details_add = false;
        $org_category_details = OrganizationUser::select('organization_categories.name', 'organization_categories.id')->join('organization_categories', 'organization_users.org_cat_id', 'organization_categories.id')->where('organization_users.id', $organization_user_id)->first();
        $org_category = $org_category_details->name;
        $organization_details = OrganizationUser::select('o.name', 'o.br_path')->join('location_organizations AS lo', 'organization_users.loc_org_id', 'lo.id')->join('organizations AS o', 'lo.org_id', 'o.id')->where('organization_users.id', $organization_user_id)->first();
        $organizations = Organization::select('id', 'name')->where('org_cat_id', $org_category_details->id)->get();

        $location_details = OrganizationUser::select(['l.phone_number', 'l.address', 'l.name AS location', 'l.postal_code', 'd.id AS district_id', 'd.name AS distict', 'p.id AS province_id', 'p.name AS province', 'o.id AS organization_id'])
            ->join('location_organizations AS lo', 'organization_users.loc_org_id', 'lo.id')
            ->join('organizations AS o', 'lo.org_id', 'o.id')
            ->join('locations AS l', 'lo.location_id', 'l.id')
            ->join('districts AS d', 'l.district_id', 'd.id')
            ->join('provinces AS p', 'l.province_id', 'p.id')
            ->where('organization_users.id', $organization_user_id)
            ->first();

        $districts = District::select('id', 'province_id', 'name')->orderBy('name', 'ASC')->get();
        $provinces = Province::select('id', 'name')->orderBy('name', 'ASC')->get();

        $br_file_url = null;
        if (!empty($organization_details->br_path) && Storage::exists($organization_details->br_path)) {
            $br_file_url = Storage::temporaryUrl($organization_details->br_path, Carbon::now()->addMinutes(10));
        }

        return view('pages.organization.dashboard.organization_details', compact('organization_user_id', 'organizations', 'organization_details_add', 'org_category', 'organization_details', 'location_details', 'districts', 'provinces', 'br_file_url'));
    }

    // Display Add Location Details
    public function addLocationDetails()
    {
        $location_details_add = true;
        $districts = District::select('id', 'province_id', 'name')->orderBy('name', 'ASC')->get();
        $provinces = Province::select('id', 'name')->orderBy('name', 'ASC')->get();
        return view('pages.organization.dashboard.location_details.add_location', compact('location_details_add', 'districts', 'provinces'));
    }

    // Display Edit Location Details
    public function editLocationDetails($loc_id)
    {
        $organization_user = Auth::guard('organization_user')->user();

        if (!$organization_user) {
            return redirect()->route('organization.login_view');
        }

        $location_details_add = false;
        $org_id = LocationOrganization::find($organization_user->loc_org_id)->org_id;

        $location_details = LocationOrganization::select(['l.phone_number', 'l.address', 'l.name AS location', 'l.postal_code', 'l.district_id', 'l.province_id',])
            ->join('locations AS l', 'location_organizations.location_id', 'l.id')
            ->where('location_organizations.org_id', $org_id)
            ->where('l.id', $loc_id)
            ->first();

        $districts = District::select('id', 'province_id', 'name')->orderBy('name', 'ASC')->get();
        $provinces = Province::select('id', 'name')->orderBy('name', 'ASC')->get();
        return view('pages.organization.dashboard.location_details.add_location', compact('loc_id', 'location_details_add', 'location_details', 'districts', 'provinces'));
    }

    // Display Manage Location Details
    public function manageLocationDetails()
    {
        // name, address, district, province, postal_code, phone_number, Actions(Edit, Delete)
        $organization_user = Auth::guard('organization_user')->user();

        if (!$organization_user) {
            return redirect()->route('organization.login_view');
        }

        $user_org_id = LocationOrganization::withTrashed()->find($organization_user->loc_org_id)->org_id;

        $locationDetails = Location::withTrashed()
            ->select(['locations.id', 'locations.name', 'locations.address', 'd.name AS district', 'p.name AS province', 'locations.postal_code', 'locations.phone_number', 'locations.deleted_at'])
            ->join('districts AS d', 'locations.district_id', 'd.id')
            ->join('provinces AS p', 'locations.province_id', 'p.id')
            ->join('location_organizations AS lo', 'locations.id', 'lo.location_id')
            ->where('lo.org_id', $user_org_id)
            ->get();

        return view('pages.organization.dashboard.location_details.manage_location', compact('locationDetails'));
    }

    // Display Add Vehicle Details
    public function addVehicleDetails()
    {
        $user = Auth::check();

        if (!$user) {
            return redirect()->route('organization.login_view');
        }
        return view('pages.organization.dashboard.vehicle_details.add_vehicle');
    }

    // Display Edit Vehicle Details
    public function editVehicleDetails($id)
    {

    }

    // Display Manage Vehicle Details
    public function manageVehicleDetails()
    {

    }
}
