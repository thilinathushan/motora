<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\LocationOrganization;
use App\Models\Vehicle;
use App\Models\VehicleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleServiceController extends Controller
{
    public function storeVehicleServiceDetails(Request $request)
    {
        $validatedData = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'registration_number' => 'required|exists:vehicles,registration_number',
            'current_milage' => 'required',
            'next_service_milage' => 'required',
            'is_engine_oil_change' => 'required',
            'is_engine_oil_filter_change' => 'required',
            'is_brake_oil_change' => 'required',
            'is_brake_pad_change' => 'required',
            'is_transmission_oil_change' => 'required',
            'is_deferential_oil_change' => 'nullable',
            'is_headlights_okay' => 'required',
            'is_signal_light_okay' => 'required',
            'is_brake_lights_okay' => 'required',
            'is_air_filter_change' => 'required',
            'is_radiator_oil_change' => 'nullable',
            'is_ac_filter_change' => 'nullable',
            'ac_gas_level' => 'nullable',
            'is_tyre_air_pressure_ok' => 'required',
            'tyre_condition' => 'required',
        ]);

        $validatedData['next_service_milage'] = $validatedData['current_milage'] + $validatedData['next_service_milage'];

        if(Auth::guard('organization_user')->check())
        {
            $isServiceCenter = Auth::guard('organization_user')->user()->isServiceCenter();
            $location_organization = LocationOrganization::select(['o.id', 'location_organizations.location_id'])
                ->join('organizations AS o', 'location_organizations.org_id', 'o.id')
                ->where('location_organizations.id', Auth::guard('organization_user')->user()->loc_org_id)
                ->first();

            if ($isServiceCenter) {
                $vehicleClass = Vehicle::where('id', $validatedData['vehicle_id'])->value('class_of_vehicle');
                $isMotorCycle = $vehicleClass === 'MOTOR CYCLE';
                $isMotorTricycle = $vehicleClass === 'MOTOR TRICYCLE';

                VehicleService::create([
                    'vehicle_id' => $validatedData['vehicle_id'],
                    'vehicle_registration_number' => $validatedData['registration_number'],
                    'current_milage' => $validatedData['current_milage'],
                    'next_service_milage' => $validatedData['next_service_milage'],
                    'is_engine_oil_change' => $validatedData['is_engine_oil_change'],
                    'is_engine_oil_filter_change' => $validatedData['is_engine_oil_filter_change'],
                    'is_brake_oil_change' => $validatedData['is_brake_oil_change'],
                    'is_brake_pad_change' => $validatedData['is_brake_pad_change'],
                    'is_transmission_oil_change' => $validatedData['is_transmission_oil_change'],
                    'is_deferential_oil_change' => ($isMotorCycle || $isMotorTricycle) ? false : $validatedData['is_deferential_oil_change'],
                    'is_headlights_okay' => $validatedData['is_headlights_okay'],
                    'is_signal_light_okay' => $validatedData['is_signal_light_okay'],
                    'is_brake_lights_okay' => $validatedData['is_brake_lights_okay'],
                    'is_air_filter_change' => $validatedData['is_air_filter_change'],
                    'is_radiator_oil_change' => ($isMotorCycle || $isMotorTricycle) ? false : $validatedData['is_radiator_oil_change'],
                    'is_ac_filter_change' => ($isMotorCycle || $isMotorTricycle) ? false : $validatedData['is_ac_filter_change'],
                    'ac_gas_level' => ($isMotorCycle || $isMotorTricycle) ? false : $validatedData['ac_gas_level'],
                    'is_tyre_air_pressure_ok' => $validatedData['is_tyre_air_pressure_ok'],
                    'tyre_condition' => $validatedData['tyre_condition'],
                    'vehicle_service_organization_id' => $location_organization->id,
                    'vehicle_service_center_id' => $location_organization->location_id,
                ]);
            }
        }
        session()->flash('success', 'Vehicle Service Record Added Successfully.');
        return redirect()->route('dashboard');
    }

    public function updateVehicleServiceDetails(Request $request, $id)
    {
        $validatedData = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'registration_number' => 'required|exists:vehicles,registration_number',
            'current_milage' => 'required',
            'next_service_milage' => 'required',
            'is_engine_oil_change' => 'required',
            'is_engine_oil_filter_change' => 'required',
            'is_brake_oil_change' => 'required',
            'is_brake_pad_change' => 'required',
            'is_transmission_oil_change' => 'required',
            'is_deferential_oil_change' => 'nullable',
            'is_headlights_okay' => 'required',
            'is_signal_light_okay' => 'required',
            'is_brake_lights_okay' => 'required',
            'is_air_filter_change' => 'required',
            'is_radiator_oil_change' => 'nullable',
            'is_ac_filter_change' => 'nullable',
            'ac_gas_level' => 'nullable',
            'is_tyre_air_pressure_ok' => 'required',
            'tyre_condition' => 'required',
        ]);

        $validatedData['next_service_milage'] = $validatedData['current_milage'] + $validatedData['next_service_milage'];

        if(Auth::guard('organization_user')->check())
        {
            $isServiceCenter = Auth::guard('organization_user')->user()->isServiceCenter();
            $location_organization = LocationOrganization::select(['o.id', 'location_organizations.location_id'])
                ->join('organizations AS o', 'location_organizations.org_id', 'o.id')
                ->where('location_organizations.id', Auth::guard('organization_user')->user()->loc_org_id)
                ->first();
            $vehicleServiceRecord = VehicleService::findOrFail($id);

            if ($isServiceCenter && ($location_organization->id == $vehicleServiceRecord->vehicle_service_organization_id) && $vehicleServiceRecord) {
                $vehicleClass = Vehicle::where('id', $validatedData['vehicle_id'])->value('class_of_vehicle');
                $isMotorCycle = $vehicleClass === 'MOTOR CYCLE';
                $isMotorTricycle = $vehicleClass === 'MOTOR TRICYCLE';

                $vehicleServiceRecord->update([
                    'current_milage' => $validatedData['current_milage'],
                    'next_service_milage' => $validatedData['next_service_milage'],
                    'is_engine_oil_change' => $validatedData['is_engine_oil_change'],
                    'is_engine_oil_filter_change' => $validatedData['is_engine_oil_filter_change'],
                    'is_brake_oil_change' => $validatedData['is_brake_oil_change'],
                    'is_brake_pad_change' => $validatedData['is_brake_pad_change'],
                    'is_transmission_oil_change' => $validatedData['is_transmission_oil_change'],
                    'is_deferential_oil_change' => ($isMotorCycle || $isMotorTricycle) ? false : $validatedData['is_deferential_oil_change'],
                    'is_headlights_okay' => $validatedData['is_headlights_okay'],
                    'is_signal_light_okay' => $validatedData['is_signal_light_okay'],
                    'is_brake_lights_okay' => $validatedData['is_brake_lights_okay'],
                    'is_air_filter_change' => $validatedData['is_air_filter_change'],
                    'is_radiator_oil_change' => ($isMotorCycle || $isMotorTricycle) ? false : $validatedData['is_radiator_oil_change'],
                    'is_ac_filter_change' => ($isMotorCycle || $isMotorTricycle) ? false : $validatedData['is_ac_filter_change'],
                    'ac_gas_level' => ($isMotorCycle || $isMotorTricycle) ? false : $validatedData['ac_gas_level'],
                    'is_tyre_air_pressure_ok' => $validatedData['is_tyre_air_pressure_ok'],
                    'tyre_condition' => $validatedData['tyre_condition'],
                ]);
            }
        }
        session()->flash('success', 'Vehicle Service Record Updated Successfully.');
        return redirect()->route('dashboard.manageVehicleServiceDetails');
    }
}
