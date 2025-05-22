<?php

namespace App\Http\Controllers\Dashboard\VehicleDetails;

use App\Http\Controllers\Controller;
use App\Models\LocationOrganization;
use App\Models\OrganizationUser;
use App\Models\UserVehicle;
use App\Models\Vehicle;
use App\Models\VehicleEmission;
use App\Models\VehicleInsurance;
use App\Models\VehicleRevenueLicense;
use App\Models\VehicleService;
use App\Models\VehicleVerificationHistory;
use App\Notifications\VehicleVerification;
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
            session()->flash('success', 'Vehicle registered successfully.');
            return redirect()->route('dashboard');
        }
        if(Auth::guard('web')->check())
        {
            $user = Auth::guard('web')->user();
            UserVehicle::create([
                'user_id' => $user->id,
                'vehicle_id' => $vehicle->id
            ]);

            return redirect()->route('dashboard.addVehicleRegCertificate', $vehicle->id)->with('success', 'Vehicle registered successfully. Now you can add the vehicle registration certificate.');
        }

    }

    public function storeVehicleRegCertificate(Request $request)
    {
        $validatedData = $request->validate([
            'vehicle_reg_certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:20480',
            'vehicle_id' => 'required|exists:vehicles,id',
        ]);

        if(Auth::guard('web')->check()){
            $vehicle = Vehicle::where('id', $validatedData['vehicle_id'])->first();
            if(isset($vehicle)){
                // get the cerificate file and store it in the storage
                $file = $request->file('vehicle_reg_certificate');
                $extension = $file->getClientOriginalExtension();

                // create the directory structure based on vehicle details
                $directory = strtolower($vehicle->provincial_council.'/'.$vehicle->year_of_manufacture.'/'.$vehicle->make.'/'.$vehicle->class_of_vehicle.'/'.$vehicle->fuel_type);

                // Rename the file using vehicle registration number
                $filename = str_replace([' ', '-'], '_', $vehicle->registration_number) . '_certificate.' . $extension;
                $directory = str_replace([' ', '-'], '_', $directory);
                
                // Store the file in the public folder with the categorized structure
                $path = $file->storeAs('vehicle_certificates/' . $directory, $filename);

                // Convert storage path to public URL
                $publicPath = 'storage/vehicle_certificates/' . $directory . '/' . $filename;

                // Update the vehicle record with the certificate path
                $vehicle->update([
                    'certificate_url' => $publicPath
                ]);
                return redirect()->route('dashboard.manageVehicleDetails')->with('success', 'Vehicle Registration Certificate Uploaded Successfully.');
            } else {
                return redirect()->back()->with('error', 'Vehicle not found.');
            }
        } else {
            return redirect()->back()->with('error', 'You are not authorized to perform this action.');
        }
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
        if(Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isServiceCenter())
        {
            return view('pages.organization.dashboard.vehicle_details.vehicle_service_details', compact('result'));
        }
        if(Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isInsuranceCompany())
        {
            return view('pages.organization.dashboard.vehicle_details.vehicle_insurance', compact('result'));
        }
        if(Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isDepartmentOfMotorTraffic())
        {
            return view('pages.organization.dashboard.vehicle_details.vehicle_ownership_details', compact('result'));
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

        $current_owner_address_idNo = str_replace(["\r", "\n", ":", ",", "."], '', $validated['current_owner_address_idNo']);
        $current_owner_address_idNo =  strtolower($current_owner_address_idNo);

        $vehicle = Vehicle::where('registration_number', $validated['registration_number'])
            ->where('chassis_number', $validated['chassis_number'])
            ->where('engine_no', $validated['engine_no'])
            ->first();

        if(isset($vehicle)){
            $vehicleOwnership = str_replace(["\r", "\n", ":", ",", "."], '', $vehicle->current_owner_address_idNo);
            $vehicleOwnership =  strtolower($vehicleOwnership);

            if($vehicleOwnership == $current_owner_address_idNo){
                $user = Auth::guard('web')->user();
                $userVehicle = UserVehicle::where('user_id', $user->id)->where('vehicle_id', $vehicle->id)->first();
                if(isset($userVehicle)){
                    return redirect()->route('dashboard.manageVehicleDetails')->with('error', 'Vehicle already assigned to you.');
                }
                UserVehicle::create([
                    'user_id' => $user->id,
                    'vehicle_id' => $vehicle->id
                ]);
                return redirect()->route('dashboard.manageVehicleDetails')->with('success', 'Vehicle Claimed Successfully.');
            } else {
                return redirect()->back()->with('error', 'Vehicle Ownership Details are not matched.');
            }
        }else{
            return redirect()->back()->with('error', 'Vehicle Details are not available.');
        }
    }

    public function unassignVehicleFromUser($id)
    {
        if(Auth::guard('web')->check())
        {
            $userVehicle = UserVehicle::where('user_id', Auth::guard('web')->user()->id)
                ->where('vehicle_id', $id)->first();

            if(isset($userVehicle))
            {
                $userVehicle->delete();
                return redirect()->route('dashboard.manageVehicleDetails')->with('success', 'Vehicle Unassigned Successfully.');
            }

        }else{
            return redirect()->back()->with('error', 'You are not authorized to perform this action.');
        }
    }

    public function changeVehicleOwnership(Request $request)
    {
        $validated = $request->validate([
            'registration_number' => 'required|max:255',
            'chassis_number' => 'required|string|max:255',
            'engine_no' => 'required|string|max:255',
            'new_owner_address_idNo' =>  'required|string',
            'new_absolute_owner' => 'required|string',
            'current_owner_address_idNo' =>  'required|string',
            'vehicle_id' =>  'required|string',
        ]);

        $vehicle = Vehicle::where('registration_number', $validated['registration_number'])
            ->where('chassis_number', $validated['chassis_number'])
            ->where('engine_no', $validated['engine_no'])
            ->first();

        if(isset($vehicle)){
            $current_owner_address_idNo = str_replace(["\r", "\n", ":", ",", "."], '', $vehicle->current_owner_address_idNo);
            $current_owner_address_idNo =  strtolower($current_owner_address_idNo);

            $absolute_owner = str_replace(["\r", "\n", ":", ",", "."], '', $vehicle->absolute_owner);
            $absolute_owner =  strtolower($absolute_owner);

            // check if current owner details and absolute owner details are same - if true
            if($current_owner_address_idNo == $absolute_owner){
                if($vehicle->previous_owners == null){
                    // abosult owner details are goes to previous owner details
                    $vehicle->update([
                        'previous_owners' => [$vehicle->absolute_owner],
                    ]);
                } else {
                    // get the previous owner details and append the absolute owner details at the end
                    $previous_owners = json_decode($vehicle->previous_owners);
                    array_push($previous_owners, $vehicle->absolute_owner);
                    $vehicle->update([
                        'previous_owners' => $previous_owners,
                    ]);
                }
            } else {
                // check if current owner details and new owner details are same - if false
                if($vehicle->previous_owners == null){
                    // abosult owner details are goes to previous owner details
                    $previous_owners = [$vehicle->absolute_owner];
                    array_push($previous_owners, $vehicle->current_owner_address_idNo);
                    $vehicle->update([
                        'previous_owners' => $previous_owners,
                    ]);
                } else {
                    $previous_owners = json_decode($vehicle->previous_owners);
                    // current owner details are goes to previous owner details;
                    array_push($previous_owners, $vehicle->absolute_owner, $vehicle->current_owner_address_idNo);
                    $vehicle->update([
                        'previous_owners' => $previous_owners,
                    ]);
                }
            }
            // new owner details and new absoulte owner details are same or not thats fine
            $vehicle->update([
                'current_owner_address_idNo' => $validated['new_owner_address_idNo'],
                'absolute_owner' => $validated['new_absolute_owner'],
            ]);
            return redirect()->route('dashboard.manageVehicleDetails')->with('success', 'Vehicle Ownership Changed Successfully.');

        } else {
            return redirect()->back()->with('error', 'Vehicle Details are not available.');
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

        if(Auth::guard('organization_user')->check())
        {
            // need to find the organization id and the organization location id
            $location_organization = LocationOrganization::select(['o.id', 'location_organizations.location_id'])
                ->join('organizations AS o', 'location_organizations.org_id', 'o.id')
                ->where('location_organizations.id', Auth::guard('organization_user')->user()->loc_org_id)
                ->first();

            $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
            VehicleRevenueLicense::create([
                'vehicle_id' => $vehicle->id,
                'vehicle_registration_number' => $vehicle->registration_number,
                'license_date' => $validated['license_date'],
                'license_no' => $validated['license_number'],
                'valid_from' => $validated['valid_from'],
                'valid_to' => $validated['valid_to'],
                'ds_organization_id' => $location_organization->id,
                'ds_center_id' => $location_organization->location_id,
            ]);

            session()->flash('success', 'Vehicle Revenue License Created Successfully.');
            return redirect()->route('dashboard.manageVehicleLicenses');
        } else {
            return redirect()->back()->with('error', 'You are not authorized to perform this action.');
        }
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

    public function verifyVehicleRegistration(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'vehicle_registration_number' => 'required|string|max:255',
            'vehicle_chassis_number' => 'required|string|max:255',
            'vehicle_engine_number' => 'required|string|max:255',
        ]);

        if(!(Auth::guard('web')->check())) {
            return redirect()->back()->with('error', 'You are not authorized to perform this action.');
        }

        $vehicle = Vehicle::select(['id', 'registration_number', 'chassis_number', 'engine_no', 'verification_score'])
            ->where('id', $validated['vehicle_id'])
            ->where('registration_number', $validated['vehicle_registration_number'])
            ->where('chassis_number', $validated['vehicle_chassis_number'])
            ->where('engine_no', $validated['vehicle_engine_number'])
            ->first();

        if (isset($vehicle) && $vehicle->verification_score < 4) {
            // find nearest divisional secretariat using latest vehicle revenue license details
            $ds_center = VehicleRevenueLicense::where('vehicle_id', $validated['vehicle_id'])
                ->where('vehicle_registration_number', $validated['vehicle_registration_number'])
                ->latest()
                ->first();

            // find nearest emission test center using latest vehicle emission details
            $emission_center = VehicleEmission::where('vehicle_id', $validated['vehicle_id'])
                ->where('vehicle_registration_number', $validated['vehicle_registration_number'])
                ->latest()
                ->first();

            // find nearest isurance company using latest vehicle insurance details
            $insurance_company = VehicleInsurance::where('vehicle_id', $validated['vehicle_id'])
                ->where('vehicle_registration_number', $validated['vehicle_registration_number'])
                ->latest()
                ->first();

            // find nearest vehicle service center using latest vehicle service details
            $service_center = VehicleService::where('vehicle_id', $validated['vehicle_id'])
                ->where('vehicle_registration_number', $validated['vehicle_registration_number'])
                ->latest()
                ->first();

            $vehicleVerificationHistory = VehicleVerificationHistory::where('vehicle_id', $validated['vehicle_id'])
                ->where('vehicle_registration_number', $validated['vehicle_registration_number'])
                ->first();

            if(isset($vehicleVerificationHistory)){

                if($vehicleVerificationHistory->ds_verification == 0){
                    // resend the verification request to divisional secretariat
                    if(isset($ds_center)){
                        $this->sendNotificationToDS($ds_center, $vehicle);

                        if($vehicleVerificationHistory->ds_organization_id == null || $vehicleVerificationHistory->ds_center_id == null){
                            $vehicleVerificationHistory->update([
                                'ds_organization_id' => $ds_center->ds_organization_id,
                                'ds_center_id' => $ds_center->ds_center_id
                            ]);
                        }
                    }
                }

                if($vehicleVerificationHistory->emission_verification == 0){
                    // resend the verification request to emission test center
                    if(isset($emission_center)){
                        $this->sendNotificationToETC($emission_center, $vehicle);

                        if($vehicleVerificationHistory->emission_organization_id == null || $vehicleVerificationHistory->emission_center_id == null){
                            $vehicleVerificationHistory->update([
                                'emission_organization_id' => $emission_center->emission_test_organization_id,
                                'emission_center_id' => $emission_center->emission_test_center_id
                            ]);
                        }
                    }
                }

                if($vehicleVerificationHistory->insurance_verification == 0){
                    // resend the verification request to insurance company
                    if(isset($insurance_company)){
                        $this->sendNotificationToInsurance($insurance_company, $vehicle);

                        if($vehicleVerificationHistory->insurance_organization_id == null || $vehicleVerificationHistory->insurance_center_id == null){
                            $vehicleVerificationHistory->update([
                                'insurance_organization_id' => $insurance_company->insurance_organization_id,
                                'insurance_center_id' => $insurance_company->insurance_center_id
                            ]);
                        }
                    }
                }

                if($vehicleVerificationHistory->service_verification == 0){
                    // resend the verification request to vehicle service center
                    if(isset($service_center)){
                        $this->sendNotificationToService($service_center, $vehicle);

                        if($vehicleVerificationHistory->service_organization_id == null || $vehicleVerificationHistory->service_center_id == null){
                            $vehicleVerificationHistory->update([
                                'service_organization_id' => $service_center->vehicle_service_organization_id,
                                'service_center_id' => $service_center->vehicle_service_center_id
                            ]);
                        }
                    }
                }

            } else {
                // send the verification request to divisional secretariat
                if(isset($ds_center)){
                    $this->sendNotificationToDS($ds_center, $vehicle);
                }

                // send the verification request to emission test center
                if(isset($emission_center)){
                    $this->sendNotificationToETC($emission_center, $vehicle);
                }

                // send the verification request to insurance company
                if(isset($insurance_company)){
                    $this->sendNotificationToInsurance($insurance_company, $vehicle);
                }

                // send the verification request to vehicle service center
                if(isset($service_center)){
                    $this->sendNotificationToService($service_center, $vehicle);
                }

                // create a new vehicle verification history
                if(isset($ds_center) || isset($emission_center) || isset($insurance_company) || isset($service_center)){
                    VehicleVerificationHistory::create([
                        'vehicle_id' => $validated['vehicle_id'],
                        'vehicle_registration_number' => $validated['vehicle_registration_number'],
                        'ds_organization_id' => isset($ds_center) ? $ds_center->ds_organization_id : null,
                        'ds_center_id' => isset($ds_center) ? $ds_center->ds_center_id : null,
                        'ds_verification' => 0,
                        'ds_verification_date' => null,
                        'emission_organization_id' => isset($emission_center) ? $emission_center->emission_test_organization_id : null,
                        'emission_center_id' => isset($emission_center) ? $emission_center->emission_test_center_id : null,
                        'emission_verification' => 0,
                        'emission_verification_date' => null,
                        'insurance_organization_id' => isset($insurance_company) ? $insurance_company->insurance_organization_id : null,
                        'insurance_center_id' => isset($insurance_company) ? $insurance_company->insurance_center_id : null,
                        'insurance_verification' => 0,
                        'insurance_verification_date' => null,
                        'service_organization_id' => isset($service_center) ? $service_center->vehicle_service_organization_id : null,
                        'service_center_id' => isset($service_center) ? $service_center->vehicle_service_center_id : null,
                        'service_verification' => 0,
                        'service_verification_date' => null,
                    ]);
                }
            }
            // display a message to the user that the verification request has been sent
            return redirect()->route('dashboard.manageVehicleDetails')->with('success', 'Verification request has been sent');
        } else {
            // display a message to the user that the vehicle is already verified
            return redirect()->route('dashboard.manageVehicleDetails')->with('error', 'Vehicle is already verified');
        }
    }

    public function sendNotificationToDS($ds_center, $vehicle)
    {
        // send the verification request to divisional secretariat
        if(isset($ds_center)){
            $location_organization = LocationOrganization::select(['location_organizations.id AS loc_org_id', 'o.org_cat_id AS org_cat_id', 'location_organizations.org_id AS org_id', 'location_organizations.location_id AS loc_id'])
                ->join('organizations AS o', 'location_organizations.org_id', 'o.id')
                ->where('org_id', $ds_center->ds_organization_id)
                ->where('location_id', $ds_center->ds_center_id)
                ->first();
        }
        if(isset($location_organization)){
            $ds_organization = OrganizationUser::where('org_cat_id', $location_organization->org_cat_id)
                ->where('loc_org_id', $location_organization->loc_org_id)
                ->first();

            if(isset($ds_organization)){
                $organization['id'] = $location_organization->org_id;
                $organization['location_id'] = $location_organization->loc_id;
                $action_url = '#';
                $ds_organization->notify(new VehicleVerification($vehicle, Auth::guard('web')->user(), $organization, $action_url));
            }
        }
    }

    public function sendNotificationToETC($emission_center, $vehicle){
        if(isset($emission_center)){
            $location_organization = LocationOrganization::select(['location_organizations.id AS loc_org_id', 'o.org_cat_id AS org_cat_id', 'location_organizations.org_id AS org_id', 'location_organizations.location_id AS loc_id'])
                ->join('organizations AS o', 'location_organizations.org_id', 'o.id')
                ->where('org_id', $emission_center->emission_test_organization_id)
                ->where('location_id', $emission_center->emission_test_center_id)
                ->first();
        }
        if(isset($location_organization)){
            $emission_organization = OrganizationUser::where('org_cat_id', $location_organization->org_cat_id)
                ->where('loc_org_id', $location_organization->loc_org_id)
                ->first();

            if(isset($emission_organization)){
                $organization['id'] = $location_organization->org_id;
                $organization['location_id'] = $location_organization->loc_id;
                $action_url = '#';
                $emission_organization->notify(new VehicleVerification($vehicle, Auth::guard('web')->user(), $organization, $action_url));
            }
        }
    }

    public function sendNotificationToInsurance($insurance_company, $vehicle){
        if(isset($insurance_company)){
            $location_organization = LocationOrganization::select(['location_organizations.id AS loc_org_id', 'o.org_cat_id AS org_cat_id', 'location_organizations.org_id AS org_id', 'location_organizations.location_id AS loc_id'])
                ->join('organizations AS o', 'location_organizations.org_id', 'o.id')
                ->where('org_id', $insurance_company->insurance_organization_id)
                ->where('location_id', $insurance_company->insurance_center_id)
                ->first();
        }
        if(isset($location_organization)){
            $insurance_organization = OrganizationUser::where('org_cat_id', $location_organization->org_cat_id)
                ->where('loc_org_id', $location_organization->loc_org_id)
                ->first();

            if(isset($insurance_organization)){
                $organization['id'] = $location_organization->org_id;
                $organization['location_id'] = $location_organization->loc_id;
                $action_url = '#';
                $insurance_organization->notify(new VehicleVerification($vehicle, Auth::guard('web')->user(), $organization, $action_url));
            }
        }
    }

    public function sendNotificationToService($service_center, $vehicle){
        if(isset($service_center)){
            $location_organization = LocationOrganization::select(['location_organizations.id AS loc_org_id', 'o.org_cat_id AS org_cat_id', 'location_organizations.org_id AS org_id', 'location_organizations.location_id AS loc_id'])
                ->join('organizations AS o', 'location_organizations.org_id', 'o.id')
                ->where('org_id', $service_center->vehicle_service_organization_id)
                ->where('location_id', $service_center->vehicle_service_center_id)
                ->first();
        }
        if(isset($location_organization)){
            $service_organization = OrganizationUser::where('org_cat_id', $location_organization->org_cat_id)
                ->where('loc_org_id', $location_organization->loc_org_id)
                ->first();

            if(isset($service_organization)){
                $organization['id'] = $location_organization->org_id;
                $organization['location_id'] = $location_organization->loc_id;
                $action_url = '#';
                $service_organization->notify(new VehicleVerification($vehicle, Auth::guard('web')->user(), $organization, $action_url));
            }
        }
    }

    public function markAllAsRead()
    {
        if(Auth::guard('web')->check()){
            return redirect()->back()->with('error', 'You are not authorized to perform this action.');
        }
        if(Auth::guard('organization_user')->check()){
            Auth::guard('organization_user')->user()->unreadNotifications->markAsRead();
            return redirect()->back()->with('success', 'All Notifications Marked As Read.');
        }
    }

    public function markAsRead($id)
    {
        if(Auth::guard('web')->check()){
            return redirect()->back()->with('error', 'You are not authorized to perform this action.');
        }
        if(Auth::guard('organization_user')->check()){
            $notification = Auth::guard('organization_user')->user()->notifications()->where('id', $id)->first();
            if(isset($notification)){
                $notification->markAsRead();
                return redirect()->back()->with('success', 'Notification Marked As Read.');
            } else {
                return redirect()->back()->with('error', 'Notification Not Found.');
            }
        }
    }

    public function verifyVehicleRegistrationDetails($id)
    {
        if(Auth::guard('web')->check()){
            return redirect()->back()->with('error', 'You are not authorized to perform this action.');
        }

        if(Auth::guard('organization_user')->check()){
            $notification = Auth::guard('organization_user')->user()->notifications()->where('id', $id)->first();
            if(isset($notification)){
                $notification->markAsRead();

                // find other same notifications for the vehicle and the organization user
                $otherNotifications = Auth::guard('organization_user')->user()->notifications()
                    ->where('data->vehicle_id', $notification->data['vehicle_id'])
                    ->where('data->vehicle_registration_number', $notification->data['vehicle_registration_number'])
                    ->where('data->requested_by_id', $notification->data['requested_by_id'])
                    ->where('data->organization_id', $notification->data['organization_id'])
                    ->where('data->location_id', $notification->data['location_id'])
                    ->whereNull('read_at')
                    ->get();

                $otherNotifications->markAsRead();

                // get the vehicle details from the notification
                $vehicle['id'] = $notification->data['vehicle_id'];
                $vehicle['vehicle_registration_number'] = $notification->data['vehicle_registration_number'];

                $vehicleVerificationHistory = VehicleVerificationHistory::where('vehicle_id', $vehicle['id'])
                    ->where('vehicle_registration_number', $vehicle['vehicle_registration_number'])
                    ->first();

                if(isset($vehicleVerificationHistory)){
                    $organization = Auth()->guard('organization_user')->user();

                    // update vehicle verification history
                    if($organization->isDivisionalSecretariat()){
                        $vehicleVerificationHistory->update([
                            'ds_verification' => 1,
                            'ds_verification_date' => Carbon::now(),
                        ]);
                    } elseif($organization->isEmissionTestCenter()){
                        $vehicleVerificationHistory->update([
                            'emission_verification' => 1,
                            'emission_verification_date' => Carbon::now(),
                        ]);
                    } elseif($organization->isInsuranceCompany()){
                        $vehicleVerificationHistory->update([
                            'insurance_verification' => 1,
                            'insurance_verification_date' => Carbon::now(),
                        ]);
                    } elseif($organization->isServiceCenter()){
                        $vehicleVerificationHistory->update([
                            'service_verification' => 1,
                            'service_verification_date' => Carbon::now(),
                        ]);
                    }

                    // update vehicle verification score
                    $vehicle = Vehicle::where('id', $vehicle['id'])
                        ->where('registration_number', $vehicle['vehicle_registration_number'])
                        ->first();


                    if(isset($vehicle)){
                        $vehicle->update([
                            'verification_score' => $vehicle->verification_score + 1,
                        ]);
                    }
                    return redirect()->route('dashboard')->with('success', 'Vehicle Registration Details Verified Successfully.');
                }
            } else {
                return redirect()->route('dashboard')->with('error', 'Notification Not Found.');
            }
        }
    }
}
