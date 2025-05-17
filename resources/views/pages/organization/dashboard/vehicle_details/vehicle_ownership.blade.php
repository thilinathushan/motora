@extends('layouts.dashboard_layout')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <h2 class="h2 my-3 fw-bold">
                    Transfer Vehicle Ownership
                </h2>
                <p class="text-muted">
                    Let's Transfer Vehicle Ownership Details 👋
                </p>
            </div>
            <div class="p-4 rounded-4">
                <form
                    action="{{ route('dashboard.vehicle.changeVehicleOwnership') }}"
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
                        <h4 class="text-muted mb-4">Ownership Details</h4>

                        <div class="mb-3 col-md-12">
                            <label for="new_owner_address_idNo" class="form-label fw-semibold">4.New
                                Owner Name/Address/I.D.No</label>
                            <textarea class="form-control" id="new_owner_address_idNo" name="new_owner_address_idNo" rows="3" required></textarea>
                            @error('new_owner_address_idNo')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="new_absolute_owner" class="form-label fw-semibold">5.New Absolute Owner</label>
                            <textarea class="form-control" id="new_absolute_owner" name="new_absolute_owner" rows="3" required></textarea>
                            @error('new_absolute_owner')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="current_owner_address_idNo" class="form-label fw-semibold">6.Current
                                Owner/Address/I.D.No</label>
                            <textarea class="form-control" id="current_owner_address_idNo" name="current_owner_address_idNo" rows="3" readonly>{{ $vehicle['current_owner_address_idNo'] }}</textarea>
                            @error('current_owner_address_idNo')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="previous_owners" class="form-label fw-semibold">7.Previous Owners</label>
                            <textarea class="form-control" id="previous_owners" name="previous_owners" rows="10" readonly>@if(isset($vehicle['previous_owners']))@foreach (json_decode($vehicle['previous_owners']) as $owner)@if(!$loop->first){{ "\n\n" }}@endif{{ $owner }}@endforeach @endif</textarea>
                            @error('previous_owners')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <input type="hidden" name="vehicle_id" id="vehicle_id" value="{{ $vehicleDetails['vehicle_id'] }}">
                    </div>

                    <button type="submit" class="btn w-100 mt-4 btn-primary">
                        Transfer Vehicle Ownership
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
