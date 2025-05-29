<?php

namespace App\Http\Controllers;

use App\Models\LocationOrganization;
use App\Models\OrganizationCategory;
use App\Models\OrganizationUser;
use App\Models\UserVehicle;
use App\Models\Vehicle;
use App\Models\VehicleEmission;
use App\Models\VehicleInsurance;
use App\Models\VehicleRevenueLicense;
use App\Models\VehicleService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OrganizationController extends Controller
{
    public function register($org_id)
    {
        $org_name = OrganizationCategory::findOrFail($org_id)->name;
        $title = $org_name.' Registration';
        return view('pages.organization.registration', compact('title'));
    }

    public function register_superAdmin(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:organization_users,email',
            'password' => 'required|min:8',
        ]);

        // get the referer route
        $referer = url()->previous();
        // find the organization name from the referer route
        $org_id = explode('/', $referer)[4];

        $organization_user = OrganizationUser::create([
            'u_tp_id' => 7, // Organization Super Admin
            'org_cat_id' => $org_id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        if($organization_user){
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);
            if(Auth::guard('organization_user')->attempt($credentials)){
                $request->session()->regenerate();
            }
            event(new Registered($organization_user));
        }

        return redirect()->route('verification.notice')->with('message', 'Please verify your email address.');
    }

    public function organization_login_selection()
    {
        $organization_categories = OrganizationCategory::all();
        $title = 'Organization Sign Up';
        return view('pages.organization.organization_select', compact('title', 'organization_categories'));
    }

    public function login_view()
    {
        $title = 'Organization Login';
        return view('pages.organization.login', compact('title'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Check OrganizationUser Guard First
        if (Auth::guard('organization_user')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        // Check Default User Guard
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        // If authentication fails
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request, WalletController $walletController)
    {
        Auth::guard('organization_user')->logout();

        // Disconnect wallet
        $walletController->disconnect();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function dashboard()
    {
        if(Auth::guard('organization_user')->check() || Auth::guard('web')->check()) {
            if(Auth::guard('organization_user')->check()){
                $user = Auth::guard('organization_user')->user();

                // Find the main location then organization location count
                if(isset($user->loc_org_id)){
                    $org_details = LocationOrganization::select(['org_id', 'location_id'])->where('id', $user->loc_org_id)->first();
                    
                    // Find the location count
                    $dashboardStat['locationCount'] = LocationOrganization::where('org_id', $org_details->org_id)->count();

                    // Find the User Count of the organization
                    $org_id = LocationOrganization::where('id', $user->loc_org_id)->value('org_id');
                    $org_loc_ids = LocationOrganization::where('org_id', $org_id)->pluck('id')->toArray();
                    $dashboardStat['userCount'] = OrganizationUser::whereIn('loc_org_id', $org_loc_ids)->count();

                    if($user->isDepartmentOfMotorTraffic()){
                        // Find the Vehicle count
                        $dashboardStat['vehicleCount'] = Vehicle::count();
                    }
                    if($user->isDivisionalSecretariat()){
                        // Find the Revenue Licences count
                        $dashboardStat['revenueLicenceCount'] = VehicleRevenueLicense::where('ds_organization_id', $org_details->org_id)
                            ->where('ds_center_id', $org_details->location_id)->count();
                    }
                    if($user->isEmissionTestCenter()){
                        // Find the Emission Test count
                        $dashboardStat['emissionTestCount'] = VehicleEmission::where('emission_test_organization_id', $org_details->org_id)
                            ->where('emission_test_center_id', $org_details->location_id)->count();
                    }
                    if($user->isInsuranceCompany()){
                        // Find the Insurance count
                        $dashboardStat['insuranceCount'] = VehicleInsurance::where('insurance_organization_id', $org_details->org_id)
                            ->where('insurance_center_id', $org_details->location_id)->count();
                    }
                    if($user->isServiceCenter()){
                        // Find the Service count
                        $dashboardStat['serviceCount'] = VehicleService::where('vehicle_service_organization_id', $org_details->org_id)
                            ->where('vehicle_service_center_id', $org_details->location_id)->count();
                    }
                } else {
                    $dashboardStat['locationCount'] = 0;
                    $dashboardStat['userCount'] = 0;
                }
            }

            if(Auth::guard('web')->check()){
                $user = Auth::guard('web')->user();
                $dashboardStat['vehicleCount'] = UserVehicle::where('user_id', $user->id)->count();
            }

            return view('pages.organization.dashboard.index', compact('dashboardStat'));
        } else {
            return redirect()->route('organization.login_view');
        }
    }
}
