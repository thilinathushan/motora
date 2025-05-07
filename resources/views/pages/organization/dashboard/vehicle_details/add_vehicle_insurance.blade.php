@extends('layouts.dashboard_layout')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <h2 class="h2 my-3 fw-bold">
                    Vehicle Insurance
                </h2>
                <p class="text-muted">
                    @if ($addVehicleInsurance)
                        Let's Add Vehicle Insurance Details 👋
                    @else
                        Let's Update Vehicle Insurance Details 👋
                    @endif
                </p>
            </div>
            <div class="p-4 rounded-4">
                <form
                    action="
                    @if ($addVehicleInsurance)
                        {{ route('dashboard.vehicleInsurance.store') }}
                    @else
                        {{ route('dashboard.vehicleInsurance.update', $vehicleDetails['id']) }}
                    @endif
                    "
                    method="post" autocomplete="true" aria-autocomplete="true">
                    @csrf
                    <div class="row">
                        <h4 class="text-muted mb-4">Vehicle Details</h4>
                        <div class="mb-3 col-md-6">
                            <label for="registration_number" class="form-label fw-semibold">1.Registration Number</label>
                            <input type="text" class="form-control" id="registration_number" name="registration_number"
                                value="{{ $vehicleDetails['registration_number'] }}" readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="chassis_number" class="form-label fw-semibold">2.Chassis Number</label>
                            <input type="text" class="form-control" id="chassis_number" name="chassis_number"
                                value="{{ $vehicleDetails['chassis_number'] }}" readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="engine_no" class="form-label fw-semibold">3.Engine Number</label>
                            <input type="text" class="form-control" id="engine_no" name="engine_no"
                                value="{{ $vehicleDetails['engine_no'] }}" readonly>
                        </div>

                        <hr class="my-3" />
                        <h4 class="text-muted mb-4">Insurance Details</h4>

                        <div class="mb-3 col-md-6">
                            <label for="policy_no" class="form-label fw-semibold">4.Policy Number</label>
                            <input type="text" class="form-control" id="policy_no" name="policy_no"
                                @if(!$addVehicleInsurance) value="{{ $vehicleDetails['policy_no'] }}" @endif required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="valid_from" class="form-label fw-semibold">5.Valid From</label>
                            <input type="date" class="form-control" id="valid_from" name="valid_from"
                                @if(!$addVehicleInsurance) value="{{ $vehicleDetails['valid_from'] }}" @endif required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="valid_to" class="form-label fw-semibold">6.Valid To</label>
                            <input type="date" class="form-control" id="valid_to" name="valid_to"
                                @if(!$addVehicleInsurance) value="{{ $vehicleDetails['valid_to'] }}" @endif required>
                        </div>

                        <input type="hidden" name="vehicle_id" id="vehicle_id" value="{{ $vehicleDetails['vehicle_id'] }}">
                    </div>

                    <button type="submit" class="btn w-100 mt-4
                        @if ($addVehicleInsurance)
                            btn-primary
                        @else
                            btn-success text-white
                        @endif
                    ">
                        @if ($addVehicleInsurance)
                            Create Vehicle Insurance
                        @else
                            Update Vehicle Insurance
                        @endif

                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
