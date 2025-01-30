<?php

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\LocationDetails\LocationController;
use App\Http\Controllers\Dashboard\Organization\OrganizationController as DashboardOrganizationController;
use App\Http\Controllers\LandingPage\LandingController;
use App\Http\Controllers\OrganizationController;
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

Route::get('/dashboard', function(){
    return view('pages.organization.dashboard.index');
})->middleware(['auth:organization_user', 'verified'])->name('dashboard');

Route::controller(OrganizationController::class)->group(function () {
    Route::get('/organization/{org_id}/register', 'register')->name('organization.register');
    Route::post('/organization/register', 'register_superAdmin')->name('organization.register_superAdmin');
    Route::get('/organization/login/', 'login_view')->name('organization.login_view');
    Route::post('/organization/login', 'login')->name('login');
    Route::post('/organization/logout', 'logout')->name('organization.logout');
    Route::get('/organization/organization_selection', 'organization_login_selection')->name('organization.organization_login_selection');
});

Route::controller(DashboardController::class)->group(function () {
    Route::get('/dashboard/organization_details', 'organizationDetails')->name('dashboard.organization_details');
    Route::get('/dashboard/location_details/add', 'addLocationDetails')->name('dashboard.addLocationDetails');
    Route::get('/dashboard/location_details/view', 'manageLocationDetails')->name('dashboard.manageLocationDetails');
});

Route::controller(DashboardOrganizationController::class)->group(function () {
    Route::post('/dashboard/organization/store', 'store_organization_details')->name('dashboard.organization.store');
    Route::post('/dashboard/organization/update/{id}', 'update_organization_details')->name('dashboard.organization.update');
});

Route::controller(LocationController::class)->group(function () {
    Route::post('/dashboard/location/store', 'store_location_details')->name('dashboard.location.store');
    Route::get('/dashboard/location/toggle-status/{id}', 'changeLocationStatus')->name('dashboard.location.toggle');
});

Route::controller(CommonController::class)->group(function () {
    Route::get('/get-province/{district_id}', 'getProvince')->name('common.getProvince');
});







Route::get('/email/verify', function () {
    $title = 'Verify Email';
    return view('components.auth.verify-email', compact('title'));
})->middleware('auth:organization_user')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard');
})->middleware(['auth:organization_user', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth:organization_user', 'throttle:6,1'])->name('verification.send');

Route::get('/check-email-verification-status', function (){
    if(auth()->user()->hasVerifiedEmail()){
        return response()->json(['verified' => true]);
    }
    return response()->json(['verified' => false]);
})->middleware('auth:organization_user');