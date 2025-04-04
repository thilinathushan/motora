<?php

namespace App\Http\Controllers\Dashboard\VehicleDetails;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
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

        $isDepartmentOfMotorTraffic = Auth::guard('organization_user')->user()->isDepartmentOfMotorTraffic();

        if ($isDepartmentOfMotorTraffic) {
            $vehicle->verification_score = 4;
            $vehicle->save();
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
}
