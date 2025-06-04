<?php

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\LocationDetails\LocationController;
use App\Http\Controllers\Dashboard\Organization\OrganizationController as DashboardOrganizationController;
use App\Http\Controllers\Dashboard\VehicleDetails\VehicleController;
use App\Http\Controllers\Dashboard\VehicleInsuranceController;
use App\Http\Controllers\Dashboard\VehicleServiceController;
use App\Http\Controllers\LandingPage\LandingController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OrganizationUserController;
use App\Http\Controllers\OrganizationUserPasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::get('/', [LandingController::class, 'index']);
Route::get('/home', function(){
    return view('pages.organization.dashboard.index');
});
Route::get('/error_page', function(){
    return view('pages.error_page');
})->name('error_page');

Route::get('/dashboard', [OrganizationController::class, 'dashboard'])->middleware(['auth:organization_user,web', 'verified'])->name('dashboard');

Route::controller(OrganizationController::class)->group(function () {
    Route::get('/organization/{org_id}/register', 'register')->name('organization.register');
    Route::post('/organization/register', 'register_superAdmin')->name('organization.register_superAdmin');
    Route::get('/organization/login/', 'login_view')->name('organization.login_view');
    Route::post('/organization/login', 'login')->name('login');
    Route::post('/organization/logout', 'logout')->name('organization.logout');
    Route::get('/organization/organization_selection', 'organization_login_selection')->name('organization.organization_login_selection');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/user/user_registration', 'user_registration')->name('user.user_registration');
    Route::post('/user/user_store', 'user_store')->name('user.user_store');
});

Route::controller(DashboardController::class)->group(function () {
    Route::get('/dashboard/organization_details', 'organizationDetails')->name('dashboard.organization_details');
    Route::get('/dashboard/location_details/add', 'addLocationDetails')->name('dashboard.addLocationDetails');
    Route::get('/dashboard/location_details/view', 'manageLocationDetails')->name('dashboard.manageLocationDetails');
    Route::get('/dashboard/location_details/edit/{id}', 'editLocationDetails')->name('dashboard.editLocationDetails');
    Route::get('/dashboard/vehicle_details/add', 'addVehicleDetails')->name('dashboard.addVehicleDetails');
    Route::get('/dashboard/vehicle_details/view', 'manageVehicleDetails')->name('dashboard.manageVehicleDetails');
    Route::get('/dashboard/vehicle_details/edit/{id}', 'editVehicleDetails')->name('dashboard.editVehicleDetails');
    Route::get('/dashboard/vehicle_details/find', 'findMyVehicleDetails')->name('dashboard.findMyVehicleDetails');
    Route::get('/dashboard/vehicle_details/claim', 'claimUserVehicle')->name('dashboard.claimUserVehicle');
    Route::get('/dashboard/vehicle_details/license', 'licenseVehicle')->name('dashboard.licenseVehicle');
    Route::get('/dashboard/vehicle_details/make_vehicle_license', 'makeVehicleLicense')->name('dashboard.makeVehicleLicense');
    Route::get('/dashboard/vehicle_details/manage_vehicle_licenses', 'manageVehicleLicenses')->name('dashboard.manageVehicleLicenses');
    Route::get('/dashboard/vehicle_details/license/edit/{id}', 'editVehicleLicenses')->name('dashboard.editVehicleLicenses');
    Route::get('/dashboard/vehicle_details/emission', 'emissionVehicle')->name('dashboard.emissionVehicle');
    Route::get('/dashboard/vehicle_details/add_vehicle_emission', 'addEmissionVehicle')->name('dashboard.addEmissionVehicle');
    Route::get('/dashboard/vehicle_details/edit_vehicle_emission/{id}/{odometer}', 'editEmissionVehicle')->name('dashboard.editEmissionVehicle');
    Route::get('/dashboard/vehicle_details/manage_vehicle_emission', 'manageEmissionVehicle')->name('dashboard.manageEmissionVehicle');
    Route::get('/dashboard/vehicle_details/vehicle_service_details', 'vehicleServiceDetails')->name('dashboard.vehicleServiceDetails');
    Route::get('/dashboard/vehicle_details/add_vehicle_service_details', 'addVehicleServiceDetails')->name('dashboard.addVehicleServiceDetails');
    Route::get('/dashboard/vehicle_details/manage_vehicle_service_records', 'manageVehicleServiceDetails')->name('dashboard.manageVehicleServiceDetails');
    Route::get('/dashboard/vehicle_details/edit_vehicle_service_record/{id}', 'editVehicleServiceDetails')->name('dashboard.editVehicleServiceDetails');
    Route::get('/dashboard/vehicle_details/vehicle_insurance_details', 'vehicleInsurance')->name('dashboard.vehicleInsurance');
    Route::get('/dashboard/vehicle_details/add_vehicle_insurance_details', 'addVehicleInsurance')->name('dashboard.addVehicleInsurance');
    Route::get('/dashboard/vehicle_details/manage_vehicle_insurance_records', 'manageVehicleInsurance')->name('dashboard.manageVehicleInsurance');
    Route::get('/dashboard/vehicle_details/edit_vehicle_insurance_record/{id}', 'editVehicleInsurance')->name('dashboard.editVehicleInsurance');
    Route::get('/dashboard/vehicle_details/findVehicleOwnership', 'findVehicleOwnership')->name('dashboard.findVehicleOwnership');
    Route::get('/dashboard/vehicle_details/viewVehicleOwnership', 'viewVehicleOwnership')->name('dashboard.viewVehicleOwnership');
    Route::get('/dashboard/vehicle/verifyVehicle/{id}', 'verifyVehicle')->name('dashboard.vehicle.verifyVehicle');
    Route::get('/dashboard/viewProfile', 'viewProfile')->name('dashboard.viewProfile');
    Route::get('/dashboard/vehicle_details/add_vehicle_reg_certificate/{id}', 'addVehicleRegCertificate')->name('dashboard.addVehicleRegCertificate');
    Route::post('/dashboard/report/faults_prediction_report', 'faultsPredictionReport')->name('dashboard.faultsPredictionReport');
    Route::get('/dashboard/report/faults_prediction_view', 'faultsPredictionView')->name('dashboard.faultsPredictionView');
    Route::post('/dashboard/report/downloadMotoraReport', 'downloadMotoraReport')->name('dashboard.vehicle.downloadMotoraReport');
})->middleware(['auth:organization_user,web', 'verified']);

Route::controller(DashboardOrganizationController::class)->group(function () {
    Route::post('/dashboard/organization/store', 'store_organization_details')->name('dashboard.organization.store');
    Route::post('/dashboard/organization/update/{id}', 'update_organization_details')->name('dashboard.organization.update');
})->middleware(['auth:organization_user,web', 'verified']);

Route::controller(LocationController::class)->group(function () {
    Route::post('/dashboard/location/store', 'store_location_details')->name('dashboard.location.store');
    Route::post('/dashboard/location/update/{id}', 'updateLocationDetails')->name('dashboard.location.update');
    Route::get('/dashboard/location/toggle-status/{id}', 'changeLocationStatus')->name('dashboard.location.toggle');
})->middleware(['auth:organization_user,web', 'verified']);

Route::controller(VehicleController::class)->group(function () {
    Route::post('/dashboard/vehicle/store', 'storeVehicledetails')->name('dashboard.vehicle.store')->middleware('crypto');
    Route::post('/dashboard/vehicle/update/{id}', 'updateVehicleDetails')->name('dashboard.vehicle.update');
    Route::post('/dashboard/vehicle/findUserVehicle', 'findUserVehicle')->name('dashboard.vehicle.findUserVehicle');
    Route::post('/dashboard/vehicle/assignVehicleToUser', 'assignVehicleToUser')->name('dashboard.vehicle.assignVehicleToUser');
    Route::get('/dashboard/vehicle/unassignVehicleFromUser/{id}', 'unassignVehicleFromUser')->name('dashboard.vehicle.unassignVehicleFromUser');
    Route::post('/dashboard/vehicle/createVehicleRevenueLicense', 'createVehicleRevenueLicense')->name('dashboard.vehicle.createVehicleRevenueLicense');
    Route::post('/dashboard/vehicle/updateVehicleRevenueLicense/{id}', 'updateVehicleRevenueLicense')->name('dashboard.vehicle.updateVehicleRevenueLicense');
    Route::post('/dashboard/vehicle/storeVehicleEmissionDetails', 'storeVehicleEmissionDetails')->name('dashboard.vehicle.storeVehicleEmissionDetails');
    Route::post('/dashboard/vehicle/updateVehicleEmissionDetails/{id}/{odometer}', 'updateVehicleEmissionDetails')->name('dashboard.vehicle.updateVehicleEmissionDetails');
    Route::post('/dashboard/vehicle/changeVehicleOwnership', 'changeVehicleOwnership')->name('dashboard.vehicle.changeVehicleOwnership');
    Route::post('/dashboard/vehicle/verifyVehicleRegistration', 'verifyVehicleRegistration')->name('dashboard.vehicle.verifyVehicleRegistration');
    Route::get('/dashboard/vehicle/markAllAsRead', 'markAllAsRead')->name('dashboard.vehicle.markAllAsRead');
    Route::get('/dashboard/vehicle/markAsRead/{id}', 'markAsRead')->name('dashboard.vehicle.markAsRead');
    Route::post('/dashboard/vehicle/verifyVehicleRegistrationDetails/{id}', 'verifyVehicleRegistrationDetails')->name('dashboard.vehicle.verifyVehicleRegistrationDetails');
    Route::post('/dashboard/vehicle/storeVehicleRegCertificate', 'storeVehicleRegCertificate')->name('dashboard.vehicle.storeVehicleRegCertificate');
    Route::post('/dashboard/vehicle/findVehicleForPrediction', 'findVehicleForPrediction')->name('dashboard.vehicle.findVehicleForPrediction');
})->middleware(['auth:organization_user,web', 'verified']);

Route::controller(VehicleServiceController::class)->group(function () {
    Route::post('/dashboard/vehicle-service/store', 'storeVehicleServiceDetails')->name('dashboard.vehicleService.store');
    Route::post('/dashboard/vehicle-service/update/{id}', 'updateVehicleServiceDetails')->name('dashboard.vehicleService.update');
})->middleware(['auth:organization_user,web', 'verified']);

Route::controller(VehicleInsuranceController::class)->group(function () {
    Route::post('/dashboard/vehicle-insurance/store', 'storeVehicleInsurance')->name('dashboard.vehicleInsurance.store');
    Route::post('/dashboard/vehicle-insurance/update/{id}', 'updateVehicleInsurance')->name('dashboard.vehicleInsurance.update');
})->middleware(['auth:organization_user,web', 'verified']);

Route::controller(CommonController::class)->group(function () {
    Route::get('/get-province/{district_id}', 'getProvince')->name('common.getProvince');
    Route::post('/getSelectedModel', 'getSelectedModel')->name('common.getSelectedModel');
    Route::post('/dashboard/generate-password', 'generatePassword')->name('dashboard.generate-password');
});

Route::controller(WalletController::class)->group(function () {
    Route::post('/wallet/connect', 'connect')->name('wallet.connect');
})->middleware(['auth:organization_user,web', 'verified']);

Route::controller(OrganizationUserController::class)->group(function () {
    Route::get('/dashboard/organization-user/index', 'index')->name('dashboard.organizationUser.index');
    Route::get('/dashboard/organization-user/create', 'create')->name('dashboard.organizationUser.create');
    Route::post('/dashboard/organization-user/store', 'store')->name('dashboard.organizationUser.store');
    Route::get('/dashboard/organization-user/edit/{id}', 'edit')->name('dashboard.organizationUser.edit');
    Route::post('/dashboard/organization-user/update/{id}', 'update')->name('dashboard.organizationUser.update');
    Route::get('/dashboard/organization-user/toggleOrganizationUser/{id}', 'toggleOrganizationUser')->name('dashboard.organizationUser.toggleOrganizationUser');
})->middleware(['auth:organization_user', 'verified']);

Route::controller(OrganizationUserPasswordController::class)->group(function () {
    Route::get('/organization-user/set-password/{user}', 'showSetPasswordForm')->name('organizationUser.setPassword')->middleware('signed');
    Route::post('/organization-user/set-password/{user}', 'submitPassword')->name('organizationUser.submitPassword');
});



Route::get('/email/verify', function () {
    $title = 'Verify Email';
    return view('components.auth.verify-email', compact('title'));
})->middleware('auth:organization_user,web')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard');
})->middleware(['auth:organization_user,web', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth:organization_user,web', 'throttle:6,1'])->name('verification.send');

Route::get('/check-email-verification-status', function (){
    if(auth()->user()->hasVerifiedEmail()){
        return response()->json(['verified' => true]);
    }
    return response()->json(['verified' => false]);
})->middleware('auth:organization_user,web');