<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Location;
use App\Models\LocationOrganization;
use App\Models\Organization;
use App\Models\OrganizationUser;
use App\Models\Province;
use App\Models\UserVehicle;
use App\Models\Vehicle;
use App\Models\VehicleEmission;
use App\Models\VehicleInsurance;
use App\Models\VehicleRevenueLicense;
use App\Models\VehicleService;
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
        $organization_user = Auth::guard('organization_user')->user();

        if (!$organization_user) {
            return redirect()->route('organization.login_view');
        }

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

        $location_details = LocationOrganization::select(['l.phone_number', 'l.address', 'l.name AS location', 'l.postal_code', 'l.district_id', 'l.province_id'])
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

        $user_org_id = LocationOrganization::withTrashed()->find($organization_user->loc_org_id);

        if (!isset($user_org_id)) {
            session()->flash('error', 'Add Organization Location First.');
            return redirect()->route('dashboard.addLocationDetails');
        }
        $user_org_id = $user_org_id->org_id;
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
        if(Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isDepartmentOfMotorTraffic()){
            if(Auth::guard('organization_user')->user()->loc_org_id == null){
                return redirect()->route('dashboard')->with('error', 'Add Organization Details First.');
            }
            $vehicle_details_add = true;
            return view('pages.organization.dashboard.vehicle_details.add_vehicle', compact('vehicle_details_add'));
        }else{
            return redirect()->route('dashboard')->with('error', 'You are not authorized to view this page.');
        }
    }

    // Display Edit Vehicle Details
    public function editVehicleDetails($id)
    {
        if(Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isDepartmentOfMotorTraffic()){
            $organization_user = Auth::guard('organization_user')->user()->isDepartmentOfMotorTraffic();

            if ($organization_user) {
                $vehicle_details_add = false;
                $vehicle_details = Vehicle::findOrFail($id);
            }

            return view('pages.organization.dashboard.vehicle_details.add_vehicle', compact('vehicle_details_add', 'vehicle_details'));
        } else {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to view this page.');
        }
    }

    // Display Manage Vehicle Details
    public function manageVehicleDetails()
    {
        if(Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isDepartmentOfMotorTraffic()){
            // check if the user is government user
            $organization_user = Auth::guard('organization_user')->user();
            if (isset($organization_user)) {
                if($organization_user->isDepartmentOfMotorTraffic()){
                    $vehicleDetails = Vehicle::get();
                }
            } else {
                $user = Auth::guard('web')->user();
                if(isset($user)){
                    $vehicleDetails = UserVehicle::join('vehicles', 'user_vehicles.vehicle_id', 'vehicles.id')
                        ->where('user_vehicles.user_id', $user->id)
                        ->select('vehicles.*')
                        ->get();
                }else{
                    return redirect()->route('organization.login_view');
                }
            }
            return view('pages.organization.dashboard.vehicle_details.manage_vehicles', compact('vehicleDetails'));
        } else {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to view this page.');
        }
    }

    // Display Find My Vehicle Details
    public function findMyVehicleDetails()
    {
        $result = false;
        return view('pages.organization.dashboard.vehicle_details.find_my_vehicle', compact('result'));
    }

    // claim user vehicle
    public function claimUserVehicle(Request $request)
    {
        $validated = $request->validate([
            'registration_number' => 'required|max:255',
            'chassis_number' => 'required|string|max:255',
            'engine_no' => 'required|string|max:255',
        ]);
        return view('pages.organization.dashboard.vehicle_details.claim_vehicle', compact('validated'));
    }

    public function licenseVehicle()
    {
        $result = false;
        return view('pages.organization.dashboard.vehicle_details.license_vehicle', compact('result'));
    }

    public function makeVehicleLicense(Request $request)
    {
        $validated = $request->validate([
            'registration_number' => 'required|max:255',
            'chassis_number' => 'required|string|max:255',
            'engine_no' => 'required|string|max:255',
        ]);

        $vehicle = Vehicle::where('registration_number', $validated['registration_number'])
            ->where('chassis_number', $validated['chassis_number'])
            ->where('engine_no', $validated['engine_no'])
            ->first();

        $addVehicleLicense = true;
        return view('pages.organization.dashboard.vehicle_details.make_vehicle_license', compact('vehicle', 'addVehicleLicense'));
    }

    public function manageVehicleLicenses()
    {
        if (Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isDivisionalSecretariat()) {
            $vehicleLicenses = VehicleRevenueLicense::join('vehicles', 'vehicle_revenue_licenses.vehicle_id', 'vehicles.id')
                ->select('vehicle_revenue_licenses.*', 'vehicles.registration_number', 'vehicles.current_owner_address_idNo', 'vehicles.unladen', 'vehicles.seating_capacity', 'vehicles.class_of_vehicle', 'vehicles.fuel_type')
                ->get();
            return view('pages.organization.dashboard.vehicle_details.manage_vehicle_licenses', compact('vehicleLicenses'));
        } else {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to view this page.');
        }
    }

    public function editVehicleLicenses($id)
    {
        if (Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isDivisionalSecretariat()) {
            $vehicleLicenses = VehicleRevenueLicense::join('vehicles', 'vehicle_revenue_licenses.vehicle_id', 'vehicles.id')
                ->select('vehicle_revenue_licenses.*', 'vehicles.registration_number', 'vehicles.current_owner_address_idNo', 'vehicles.unladen', 'vehicles.seating_capacity', 'vehicles.class_of_vehicle', 'vehicles.fuel_type')
                ->where('vehicle_revenue_licenses.id', $id)
                ->first();

            if(!isset($vehicleLicenses)){
                return redirect()->route('dashboard.manageVehicleLicenses')->with('error', 'Vehicle License not found.');
            }
            $addVehicleLicense = false;
            return view('pages.organization.dashboard.vehicle_details.make_vehicle_license', compact('vehicleLicenses', 'addVehicleLicense'));
        }else{
            return redirect()->route('dashboard')->with('error', 'You are not authorized to view this page.');
        }
    }

    public function emissionVehicle()
    {
        $result = false;
        return view('pages.organization.dashboard.vehicle_details.emission_vehicle', compact('result'));
    }

    public function addEmissionVehicle(Request $request)
    {
        $vehicle = Vehicle::select(['id', 'registration_number', 'class_of_vehicle', 'engine_no', 'chassis_number', 'make', 'model', 'year_of_manufacture', 'fuel_type'])->findOrFail($request->vehicle_id);
        if(Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isEmissionTestCenter()){
            $org_user = Auth::guard('organization_user')->user();
            if($org_user->loc_org_id == null){
                return redirect()->route('dashboard')->with('error', 'Add Organization Details First.');
            }
            $org_details = OrganizationUser::join('location_organizations', 'organization_users.loc_org_id', 'location_organizations.id')
                ->join('organizations AS org', 'location_organizations.org_id', 'org.id')
                ->join('locations AS loc', 'location_organizations.location_id', 'loc.id')
                ->select(['org.name AS org_name', 'org.id AS org_id', 'loc.name AS loc_name', 'loc.id AS loc_id'])
                ->where('organization_users.id', $org_user->id)
                ->first();

            $result['vehicle'] = $vehicle;
            $result['org_details'] = $org_details;
            $addVehicleLEmission = true;
            return view('pages.organization.dashboard.vehicle_details.add_emission_vehicle', compact('result', 'addVehicleLEmission'));
        } else {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to view this page.');
        }
    }

    public function manageEmissionVehicle()
    {
        if(Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isEmissionTestCenter())
        {
            $organization_user = Auth::guard('organization_user')->user();

            $org_id = OrganizationUser::join('location_organizations AS lo', 'organization_users.loc_org_id', 'lo.id')
                ->where('organization_users.id', $organization_user->id)->value('lo.org_id');

            $vehicleEmissions = VehicleEmission::join('vehicles AS v', 'vehicle_emissions.vehicle_id', 'v.id')
                ->join('locations AS l', 'vehicle_emissions.emission_test_center_id', 'l.id')
                ->select([
                    'vehicle_emissions.*',
                    'v.registration_number',
                    'v.class_of_vehicle',
                    'v.engine_no',
                    'v.chassis_number',
                    'v.make',
                    'v.model',
                    'v.year_of_manufacture',
                    'v.fuel_type',
                    'l.name AS loc_name',
                ])
                ->where('emission_test_organization_id', $org_id)
                ->get();
            return view('pages.organization.dashboard.vehicle_details.manage_vehicle_emissions', compact('vehicleEmissions'));
        }else{
            return redirect()->route('dashboard')->with('error', 'You are not authorized to view this page.');
        }
    }

    public function editEmissionVehicle($id, $odometer)
    {
        $vehicle = Vehicle::select(['id', 'registration_number', 'class_of_vehicle', 'engine_no', 'chassis_number', 'make', 'model', 'year_of_manufacture', 'fuel_type'])
                ->findOrFail($id);
        if(Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isEmissionTestCenter()){
            $org_user = Auth::guard('organization_user')->user();

            $org_details = OrganizationUser::join('location_organizations', 'organization_users.loc_org_id', 'location_organizations.id')
                ->join('organizations AS org', 'location_organizations.org_id', 'org.id')
                ->join('locations AS loc', 'location_organizations.location_id', 'loc.id')
                ->select(['org.name AS org_name', 'org.id AS org_id', 'loc.name AS loc_name', 'loc.id AS loc_id'])
                ->where('organization_users.id', $org_user->id)
                ->first();

            $vehicleEmission = VehicleEmission::select(['id', 'odometer', 'rpm_idle', 'hc_idle', 'co_idle', 'rpm_2500', 'hc_2500', 'co_2500', 'average_k_factor'])
                ->where('vehicle_id', $id)
                ->where('odometer', $odometer)
                ->first();

            $result['vehicle'] = $vehicle;
            $result['org_details'] = $org_details;
            $result['vehicleEmission'] = $vehicleEmission;
            $addVehicleLEmission = false;
            return view('pages.organization.dashboard.vehicle_details.add_emission_vehicle', compact('result', 'addVehicleLEmission'));
        } else {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to view this page.');
        }

    }

    public function vehicleServiceDetails()
    {
        $result = false;
        return view('pages.organization.dashboard.vehicle_details.vehicle_service_details', compact('result'));
    }

    public function addVehicleServiceDetails(Request $request)
    {
        if(Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isServiceCenter()){
            if(Auth::guard('organization_user')->user()->loc_org_id == null){
                return redirect()->route('dashboard')->with('error', 'Add Organization Details First.');
            }

            $addVehicleService = true;
            $vehicleDetails = $request->all();
            return view('pages.organization.dashboard.vehicle_details.add_vehicle_service_details', compact('addVehicleService', 'vehicleDetails'));
        } else {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to view this page.');
        }
    }

    public function manageVehicleServiceDetails()
    {
        if(Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isServiceCenter()){
            $org_user = Auth::guard('organization_user')->user();
            $org_id = OrganizationUser::join('location_organizations AS lo', 'organization_users.loc_org_id', 'lo.id')
                ->where('organization_users.id', $org_user->id)->value('lo.org_id');


            $vehicleServiceDetails = VehicleService::select([
                    'vehicle_services.*',
                    'o.name AS org_name',
                    'l.name AS loc_name',
                ])
                ->join('organizations AS o', 'o.id', 'vehicle_services.vehicle_service_organization_id')
                ->join('locations AS l', 'l.id', 'vehicle_services.vehicle_service_center_id')
                ->where('vehicle_service_organization_id', $org_id)
                ->get();

            return view('pages.organization.dashboard.vehicle_details.manage_vehicle_service_details', compact('vehicleServiceDetails'));
        } else {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to view this page.');
        }
    }

    public function editVehicleServiceDetails($id)
    {
        if(Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isServiceCenter()){
            $org_user = Auth::guard('organization_user')->user();
            $org_id = OrganizationUser::join('location_organizations AS lo', 'organization_users.loc_org_id', 'lo.id')
                ->where('organization_users.id', $org_user->id)->value('lo.org_id');

            $vehicleDetails = VehicleService::select([
                    'vehicle_services.*',
                    'o.name AS org_name',
                    'l.name AS loc_name',
                    'v.chassis_number',
                    'v.engine_no',
                    'v.registration_number',
                ])
                ->join('vehicles AS v', 'v.id', 'vehicle_services.vehicle_id')
                ->join('organizations AS o', 'o.id', 'vehicle_services.vehicle_service_organization_id')
                ->join('locations AS l', 'l.id', 'vehicle_services.vehicle_service_center_id')
                ->where('vehicle_services.id', $id)
                ->where('vehicle_service_organization_id', $org_id)
                ->first();

            if(!isset($vehicleDetails)){
                return redirect()->route('dashboard.manageVehicleServiceDetails')->with('error', 'Vehicle Service Details not found.');
            }
            $addVehicleService = false;

            return view('pages.organization.dashboard.vehicle_details.add_vehicle_service_details', compact('addVehicleService', 'vehicleDetails'));
        } else {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to view this page.');
        }
    }

    public function vehicleInsurance()
    {
        $result = false;
        return view('pages.organization.dashboard.vehicle_details.vehicle_service_details', compact('result'));
    }

    public function addVehicleInsurance(Request $request)
    {
        if(Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isInsuranceCompany()){
            if(Auth::guard('organization_user')->user()->loc_org_id == null){
                return redirect()->route('dashboard')->with('error', 'Add Organization Details First.');
            }

            $addVehicleInsurance = true;
            $vehicleDetails = $request->all();
            return view('pages.organization.dashboard.vehicle_details.add_vehicle_insurance', compact('addVehicleInsurance', 'vehicleDetails'));
        } else {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to view this page.');
        }
    }

    public function manageVehicleInsurance()
    {
        if(Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isInsuranceCompany()){
            $org_user = Auth::guard('organization_user')->user();
            $org_id = OrganizationUser::join('location_organizations AS lo', 'organization_users.loc_org_id', 'lo.id')
                ->where('organization_users.id', $org_user->id)->value('lo.org_id');

            $vehicleInsuranceDetails = VehicleInsurance::select([
                    'vehicle_insurances.*',
                    'o.name AS org_name',
                    'l.name AS loc_name',
                ])
                ->join('organizations AS o', 'o.id', 'vehicle_insurances.insurance_organization_id')
                ->join('locations AS l', 'l.id', 'vehicle_insurances.insurance_center_id')
                ->where('insurance_organization_id', $org_id)
                ->get();
            return view('pages.organization.dashboard.vehicle_details.manage_vehicle_insurance', compact('vehicleInsuranceDetails'));
        } else {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to view this page.');
        }
    }

    public function editVehicleInsurance($id)
    {
        if(Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isInsuranceCompany()){
            $org_user = Auth::guard('organization_user')->user();
            $org_id = OrganizationUser::join('location_organizations AS lo', 'organization_users.loc_org_id', 'lo.id')
                ->where('organization_users.id', $org_user->id)->value('lo.org_id');

            $addVehicleInsurance = false;
            $vehicleDetails = VehicleInsurance::select([
                    'vehicle_insurances.*',
                    'o.name AS org_name',
                    'l.name AS loc_name',
                    'v.chassis_number',
                    'v.engine_no',
                    'v.registration_number',
                ])
                ->join('vehicles AS v', 'v.id', 'vehicle_insurances.vehicle_id')
                ->join('organizations AS o', 'o.id', 'vehicle_insurances.insurance_organization_id')
                ->join('locations AS l', 'l.id', 'vehicle_insurances.insurance_center_id')
                ->where('vehicle_insurances.id', $id)
                ->where('insurance_organization_id', $org_id)
                ->first();

            return view('pages.organization.dashboard.vehicle_details.add_vehicle_insurance', compact('addVehicleInsurance', 'vehicleDetails'));

        } else {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to view this page.');
        }
    }

    
}
