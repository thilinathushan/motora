<?php

namespace App\Http\Controllers\Dashboard\VehicleDetails;

use App\Http\Controllers\Controller;
use App\Models\UserVehicle;
use App\Models\Vehicle;
use App\Models\VehicleEmission;
use App\Models\VehicleRevenueLicense;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    public function storeVehicledetails(Request $request)
    {
        $validatedData = $request->validate([
            'registration_number' => 'required|unique:vehicles,registration_number|max:255',
            'chassis_number' => 'required|string|unique:vehicles,chassis_number|max:255',
            'current_owner_address_idNo' => 'required|string',
            'conditions_special_notes' => 'required|string',
            'absolute_owner' => 'required|string|max:255',
            'engine_no' => 'required|string|unique:vehicles,engine_no|max:255',
            'cylinder_capacity' => 'required',
            'class_of_vehicle' => 'required|string|max:255',
            'taxation_class' => 'required|string|max:255',
            'status_when_registered' => 'required|string|max:255',
            'fuel_type' => 'required|string|max:255',
            'make' => 'required|string|max:255',
            'country_of_origin' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'manufactures_description' => 'required|string',
            'wheel_base' => 'required|string|max:255',
            'over_hang' => 'nullable|string|max:255',
            'type_of_body' => 'required|string|max:255',
            'year_of_manufacture' => 'required|integer|min:1800|max:' . date('Y'),
            'colour' => 'required|string|max:255',
            'previous_owners' => 'nullable|string',
            'seating_capacity' => 'required',
            'unladen' => 'required|string|max:255',
            'gross' => 'nullable|string|max:255',
            'front' => 'required|string|max:255',
            'rear' => 'required|string|max:255',
            'dual' => 'required|string|max:255',
            'single' => 'required|string|max:255',
            'length_width_height' => 'required|string|max:255',
            'provincial_council' => 'required|string|max:255',
            'date_of_first_registration' => 'required|date|before_or_equal:today',
            'taxes_payable' => 'nullable|string|max:255',
        ]);

        $vehicle = Vehicle::create($validatedData);
        if(Auth::guard('organization_user')->check())
        {
            $isDepartmentOfMotorTraffic = Auth::guard('organization_user')->user()->isDepartmentOfMotorTraffic();
            if ($isDepartmentOfMotorTraffic) {
                $vehicle->verification_score = 4;
                $vehicle->save();
            }
        }
        if(Auth::guard('web')->check())
        {
            $user = Auth::guard('web')->user();
            UserVehicle::create([
                'user_id' => $user->id,
                'vehicle_id' => $vehicle->id
            ]);
        }

        session()->flash('success', 'Vehicle registered successfully.');
        return redirect()->route('dashboard');
    }

    public function updateVehicleDetails(Request $request, $id)
    {
        $validatedData = $request->validate([
            'registration_number' => 'required|exists:vehicles,registration_number|max:255',
            'chassis_number' => 'required|string|exists:vehicles,chassis_number|max:255',
            'current_owner_address_idNo' => 'required|string',
            'conditions_special_notes' => 'required|string',
            'absolute_owner' => 'required|string|max:255',
            'engine_no' => 'required|string|exists:vehicles,engine_no|max:255',
            'cylinder_capacity' => 'required',
            'class_of_vehicle' => 'required|string|max:255',
            'taxation_class' => 'required|string|max:255',
            'status_when_registered' => 'required|string|max:255',
            'fuel_type' => 'required|string|max:255',
            'make' => 'required|string|max:255',
            'country_of_origin' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'manufactures_description' => 'required|string',
            'wheel_base' => 'required|string|max:255',
            'over_hang' => 'nullable|string|max:255',
            'type_of_body' => 'required|string|max:255',
            'year_of_manufacture' => 'required|integer|min:1800|max:' . date('Y'),
            'colour' => 'required|string|max:255',
            'previous_owners' => 'nullable|string',
            'seating_capacity' => 'required',
            'unladen' => 'required|string|max:255',
            'gross' => 'nullable|string|max:255',
            'front' => 'required|string|max:255',
            'rear' => 'required|string|max:255',
            'dual' => 'required|string|max:255',
            'single' => 'required|string|max:255',
            'length_width_height' => 'required|string|max:255',
            'provincial_council' => 'required|string|max:255',
            'date_of_first_registration' => 'required|date|before_or_equal:today',
            'taxes_payable' => 'nullable|string|max:255',
        ]);

        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update($validatedData);

        // ✅ Success Message & Redirect
        session()->flash('success', 'Vehicle Details Updated Successfully.');
        return redirect()->route('dashboard.manageVehicleDetails');
    }

    public function changeVehicleStatus($id)
    {

    }

    public function findUserVehicle(Request $request)
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
        if(isset($vehicle)){
            $result = [
                'status' => 'success',
                'vehicle_id' => $vehicle->id,
                'registration_number' => $vehicle->registration_number,
                'chassis_number' => $vehicle->chassis_number,
                'engine_no' => $vehicle->engine_no,
            ];
        }else{
            $result = [
                'status' => 'error',
                'message' => 'Vehicle not found.',
            ];
        }
        if(Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isDivisionalSecretariat())
        {
            return view('pages.organization.dashboard.vehicle_details.license_vehicle', compact('result'));
        }
        if(Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isEmissionTestCenter())
        {
            return view('pages.organization.dashboard.vehicle_details.emission_vehicle', compact('result'));
        }
        return view('pages.organization.dashboard.vehicle_details.find_my_vehicle', compact('result'));
    }

    public function assignVehicleToUser(Request $request)
    {
        $validated = $request->validate([
            'registration_number' => 'required|max:255',
            'chassis_number' => 'required|string|max:255',
            'engine_no' => 'required|string|max:255',
            'current_owner_address_idNo' =>  'required|string',
        ]);

        $vehicle = Vehicle::where('registration_number', $validated['registration_number'])
        ->where('chassis_number', $validated['chassis_number'])
        ->where('engine_no', $validated['engine_no'])
        ->where('current_owner_address_idNo', $validated['current_owner_address_idNo'])
        ->first();

        if(isset($vehicle)){
            $user = Auth::guard('web')->user();
            UserVehicle::create([
                'user_id' => $user->id,
                'vehicle_id' => $vehicle->id
            ]);
            return redirect()->route('dashboard.manageVehicleDetails')->with('success', 'Vehicle Claimed Successfully.');
        }else{
            return redirect()->back()->with('error', 'Vehicle Ownership Details are not correct.');
        }
    }

    public function createVehicleRevenueLicense(Request $request)
    {
        $validated = $request->validate([
            'license_date' => 'required',
            'license_number' => 'required|string|max:255',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after_or_equal:valid_from',
            'vehicle_id' => 'required|integer',
        ]);

        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
        VehicleRevenueLicense::create([
            'vehicle_id' => $vehicle->id,
            'vehicle_registration_number' => $vehicle->registration_number,
            'license_date' => $validated['license_date'],
            'license_no' => $validated['license_number'],
            'valid_from' => $validated['valid_from'],
            'valid_to' => $validated['valid_to']
        ]);

        session()->flash('success', 'Vehicle Revenue License Created Successfully.');
        return redirect()->route('dashboard.manageVehicleLicenses');
    }

    public function updateVehicleRevenueLicense(Request $request, $id)
    {
        $validated = $request->validate([
            'license_date' => 'required',
            'license_number' => 'required|string|max:255',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after_or_equal:valid_from',
        ]);

        $vehicleRevenueLicense = VehicleRevenueLicense::findOrFail($id);
        $vehicleRevenueLicense->update([
            'license_date' => $validated['license_date'],
            'license_no' => $validated['license_number'],
            'valid_from' => $validated['valid_from'],
            'valid_to' => $validated['valid_to']
        ]);

        session()->flash('success', 'Vehicle Revenue License Updated Successfully.');
        return redirect()->route('dashboard.manageVehicleLicenses');
    }

    public function storeVehicleEmissionDetails(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'registration_number' => 'required|string|max:255',
            'odometer' => 'required|string|max:255',
            'rpm_idle' => 'string|max:255',
            'hc_idle' => 'string|max:255',
            'co_idle' => 'string|max:255',
            'rpm_2500' => 'string|max:255',
            'hc_2500' => 'string|max:255',
            'co_2500' => 'string|max:255',
            'average_k_factor' => 'string|max:255',
            'overall_status' => 'required|string|max:255',
            'date_of_issue' => 'required|date',
            'emission_test_organization_id' => 'required|exists:organizations,id',
            'emission_test_center_id' => 'required|exists:locations,id',
        ]);

        $validated['valid_to'] = Carbon::parse($validated['date_of_issue'])->addYear()->format('Y-m-d');

        VehicleEmission::create([
            'vehicle_id' => $validated['vehicle_id'],
            'vehicle_registration_number' => $validated['registration_number'],
            'odometer' => $validated['odometer'],
            'rpm_idle' => isset($validated['rpm_idle']) ? $validated['rpm_idle'] : null,
            'hc_idle' => isset($validated['hc_idle']) ? $validated['hc_idle'] : null,
            'co_idle' => isset($validated['co_idle']) ? $validated['co_idle'] : null,
            'rpm_2500' => isset($validated['rpm_2500']) ? $validated['rpm_2500'] : null,
            'hc_2500' => isset($validated['hc_2500']) ? $validated['hc_2500'] : null,
            'co_2500' => isset($validated['co_2500']) ? $validated['co_2500'] : null,
            'average_k_factor' => isset($validated['average_k_factor']) ? $validated['average_k_factor'] : null,
            'overall_status' => $validated['overall_status'],
            'valid_from' => $validated['date_of_issue'],
            'valid_to' => $validated['valid_to'],
            'emission_test_organization_id' => $validated['emission_test_organization_id'],
            'emission_test_center_id' => $validated['emission_test_center_id'],
        ]);

        session()->flash('success', 'Vehicle Emission Details Created Successfully.');
        return redirect()->route('dashboard.manageEmissionVehicle');
    }

    public function updateVehicleEmissionDetails(Request $request, $id, $odometer)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'registration_number' => 'required|string|max:255',
            'odometer' => 'required|string|max:255',
            'rpm_idle' => 'string|max:255',
            'hc_idle' => 'string|max:255',
            'co_idle' => 'string|max:255',
            'rpm_2500' => 'string|max:255',
            'hc_2500' => 'string|max:255',
            'co_2500' => 'string|max:255',
            'average_k_factor' => 'string|max:255',
            'overall_status' => 'required|string|max:255',
            'date_of_issue' => 'required|date',
            'emission_test_organization_id' => 'required|exists:organizations,id',
            'emission_test_center_id' => 'required|exists:locations,id',
        ]);

        $validated['valid_to'] = Carbon::parse($validated['date_of_issue'])->addYear()->format('Y-m-d');
        $vehicleEmission = VehicleEmission::where('vehicle_id', $id)->where('odometer', $odometer)->first();

        $vehicleEmission->update([
            'odometer' => $validated['odometer'],
            'rpm_idle' => isset($validated['rpm_idle']) ? $validated['rpm_idle'] : null,
            'hc_idle' => isset($validated['hc_idle']) ? $validated['hc_idle'] : null,
            'co_idle' => isset($validated['co_idle']) ? $validated['co_idle'] : null,
            'rpm_2500' => isset($validated['rpm_2500']) ? $validated['rpm_2500'] : null,
            'hc_2500' => isset($validated['hc_2500']) ? $validated['hc_2500'] : null,
            'co_2500' => isset($validated['co_2500']) ? $validated['co_2500'] : null,
            'average_k_factor' => isset($validated['average_k_factor']) ? $validated['average_k_factor'] : null,
            'overall_status' => $validated['overall_status']
        ]);

        session()->flash('success', 'Vehicle Emission Details Updated Successfully.');
        return redirect()->route('dashboard.manageEmissionVehicle');
    }
}
