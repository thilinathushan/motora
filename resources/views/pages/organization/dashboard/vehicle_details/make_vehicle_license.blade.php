
@extends('layouts.dashboard_layout')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <h2 class="h2 my-3 fw-bold">
                    Vehicle License Details
                </h2>
                <p class="text-muted">Let's license your vehicle 👋</p>
            </div>
            <div class="p-4 rounded-4">
                <form action="
                    @if ($addVehicleLicense)
                        {{ route('dashboard.vehicle.createVehicleRevenueLicense') }}
                    @else
                        {{ route('dashboard.vehicle.updateVehicleRevenueLicense', $vehicleLicenses->id) }}
                    @endif" method="post" autocomplete="true" aria-autocomplete="true">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="license_date" class="form-label fw-semibold">1.License Date</label>
                            <input type="month" class="form-control" id="license_date"
                                name="license_date" @if(!$addVehicleLicense) value="{{ $vehicleLicenses->license_date }}"@endif required>
                            @error('license_date')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="license_number" class="form-label fw-semibold">2.License No</label>
                            <input type="text" class="form-control" id="license_number" name="license_number"
                                @if(!$addVehicleLicense) value="{{ $vehicleLicenses->license_no }}"@endif required>
                            @error('license_number')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="vehicle_details" class="form-label fw-semibold">3.Class of Vehicle, Fuel Type, Vehicle No</label>
                            <input type="text" name="vehicle_details" id="vehicle_details" class="form-control" @if($addVehicleLicense) value="{{ $vehicle->class_of_vehicle }} | {{ $vehicle->fuel_type }} | {{ $vehicle->registration_number }}" @else value="{{ $vehicleLicenses->class_of_vehicle }} | {{ $vehicleLicenses->fuel_type }} | {{ $vehicleLicenses->registration_number }}"@endif readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="owner_details" class="form-label fw-semibold">4.Name and Address of the Owner</label>
                            <textarea class="form-control" name="owner_details" id="owner_details" cols="30" rows="5" readonly>@if($addVehicleLicense){{ $vehicle->current_owner_address_idNo }}@else{{ $vehicleLicenses->current_owner_address_idNo }}@endif</textarea>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="weight" class="form-label fw-semibold">5.Unladen/Gross Weight</label>
                            <input type="text" name="weight" id="weight" class="form-control" @if($addVehicleLicense) value="{{ $vehicle->unladen }}" @else value="{{ $vehicleLicenses->unladen }}"@endif readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="no_of_seats" class="form-label fw-semibold">6.No of Seats</label>
                            <input type="text" name="no_of_seats" id="no_of_seats" class="form-control" @if($addVehicleLicense) value="{{ $vehicle->seating_capacity }}" @else value="{{ $vehicleLicenses->seating_capacity }}"@endif readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="license_date_valid" class="form-label fw-semibold">7.License Date Valid</label>
                            <div class="row" id="license_date_valid">
                                <div class="col form-floating">
                                    <input type="date" name="valid_from" id="valid_from" class="form-control" @if(!$addVehicleLicense) value="{{ $vehicleLicenses->valid_from }}"@endif required>
                                    <label for="valid_from" class="form-label fw-semibold">From</label>
                                </div>
                                <div class="col form-floating">
                                    <input type="date" name="valid_to" id="valid_to" class="form-control" @if(!$addVehicleLicense) value="{{ $vehicleLicenses->valid_to }}"@endif required>
                                    <label for="valid_to" class="form-label fw-semibold">To</label>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="vehicle_id" id="vehicle_id" @if($addVehicleLicense) value="{{ $vehicle->id }}"@endif>
                    </div>

                    <button type="submit" class="btn w-100 mt-4
                        @if ($addVehicleLicense)
                            btn-primary
                        @else
                            btn-success text-white
                        @endif
                    ">@if ($addVehicleLicense)
                            Create Vehicle License
                        @else
                            Update Vehicle License
                        @endif
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
