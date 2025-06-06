<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\LocationOrganization;
use App\Models\OrganizationUser;
use App\Models\VehicleInsurance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleInsuranceController extends Controller
{
    public function storeVehicleInsurance(Request $request)
    {
        $validatedData = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'registration_number' => 'required',
            'chassis_number' => 'required',
            'engine_no' => 'required',
            'policy_no' => 'required',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after:valid_from',
        ]);

        if(Auth::guard('organization_user')->check())
        {
            $isInsuranceCompany = Auth::guard('organization_user')->user()->isInsuranceCompany();
            $location_organization = LocationOrganization::select(['o.id', 'location_organizations.location_id'])
                ->join('organizations AS o', 'location_organizations.org_id', 'o.id')
                ->where('location_organizations.id', Auth::guard('organization_user')->user()->loc_org_id)
                ->first();

            if ($isInsuranceCompany) {
                VehicleInsurance::create([
                    'vehicle_id' => $validatedData['vehicle_id'],
                    'vehicle_registration_number' => $validatedData['registration_number'],
                    'policy_no' => $validatedData['policy_no'],
                    'valid_from' => $validatedData['valid_from'],
                    'valid_to' => $validatedData['valid_to'],
                    'insurance_organization_id' => $location_organization->id,
                    'insurance_center_id' => $location_organization->location_id,
                ]);
            }
        }
        session()->flash('success', 'Vehicle Insurance Record Added Successfully.');
        return redirect()->route('dashboard');
    }

    public function updateVehicleInsurance(Request $request, $id)
    {
        if(Auth::guard('organization_user')->check() &&
            Auth::guard('organization_user')->user()->hasCategory('Insurance Company') &&
            Auth::guard('organization_user')->user()->hasRole('Organization Employee'))
        {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access this page.');
        }
        $validatedData = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'registration_number' => 'required',
            'chassis_number' => 'required',
            'engine_no' => 'required',
            'policy_no' => 'required',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after:valid_from',
        ]);

        if(Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isInsuranceCompany())
        {
            $org_user = Auth::guard('organization_user')->user();
            $org_id = OrganizationUser::join('location_organizations AS lo', 'organization_users.loc_org_id', 'lo.id')
                ->where('organization_users.id', $org_user->id)->value('lo.org_id');

            $vehicleInsurance = VehicleInsurance::where('id', $id)
                ->where('insurance_organization_id', $org_id)
                ->first();

            if ($vehicleInsurance) {
                $vehicleInsurance->update([
                    'policy_no' => $validatedData['policy_no'],
                    'valid_from' => $validatedData['valid_from'],
                    'valid_to' => $validatedData['valid_to'],
                ]);
            }

            session()->flash('success', 'Vehicle Insurance Record Updated Successfully.');
            return redirect()->route('dashboard');
        }
    }
}
