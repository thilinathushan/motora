@extends('layouts.dashboard_layout')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <h2 class="h2 my-3 fw-bold">
                    @if ($vehicle_details_add)
                        Add Vehicle Details
                    @else
                        Edit Vehicle Details
                    @endif
                </h2>
            </div>
            <div class="p-4 rounded-4" style="background-color: #fdeff4">
                <form action="
                    @if ($vehicle_details_add)
                        {{ route('dashboard.vehicle.store') }}
                    @else
                        {{ route('dashboard.vehicle.update', $vehicle_details->id) }}
                    @endif
                    " method="post" autocomplete="true"
                    aria-autocomplete="true" a>
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="registration_number" class="form-label fw-semibold">1.Registration Number</label>
                            <input type="text" class="form-control" id="registration_number" name="registration_number"
                               value="@if(!$vehicle_details_add){{ $vehicle_details->registration_number }}@endif" required>
                            @error('registration_number')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="chassis_number" class="form-label fw-semibold">2.Chassis Number</label>
                            <input type="text" class="form-control" id="chassis_number" name="chassis_number" value="@if(!$vehicle_details_add){{ $vehicle_details->chassis_number }}@endif" required>
                            @error('chassis_number')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="current_owner_address_idNo" class="form-label fw-semibold">3.Current
                                Owner/Address/I.D.No</label>
                            <textarea class="form-control" id="current_owner_address_idNo" name="current_owner_address_idNo" rows="3">@if(!$vehicle_details_add){{ $vehicle_details->current_owner_address_idNo }}@endif</textarea>
                            @error('current_owner_address_idNo')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="conditions_special_notes" class="form-label fw-semibold">4.Conditions/Special
                                Notes</label>
                            <input type="text" class="form-control" id="conditions_special_notes"
                                name="conditions_special_notes" value="@if(!$vehicle_details_add){{ $vehicle_details->conditions_special_notes }}@endif" required>
                            @error('conditions_special_notes')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="absolute_owner" class="form-label fw-semibold">5.Absolute Owner</label>
                            <textarea class="form-control" id="absolute_owner" name="absolute_owner" rows="3">@if(!$vehicle_details_add){{ $vehicle_details->absolute_owner }}@endif</textarea>
                            @error('absolute_owner')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="engine_no" class="form-label fw-semibold">6.Engine No</label>
                            <input type="text" class="form-control" id="engine_no" name="engine_no" value="@if(!$vehicle_details_add){{ $vehicle_details->engine_no }}@endif" required>
                            @error('engine_no')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="cylinder_capacity" class="form-label fw-semibold">7.Cylinder Capacity (CC)</label>
                            <input type="text" class="form-control" id="cylinder_capacity" name="cylinder_capacity" value="@if(!$vehicle_details_add){{ $vehicle_details->cylinder_capacity }}@endif"
                                required>
                            @error('cylinder_capacity')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="class_of_vehicle" class="form-label fw-semibold">8.Class of Vehicle</label>
                            <input type="text" class="form-control" id="class_of_vehicle" name="class_of_vehicle" value="@if(!$vehicle_details_add){{ $vehicle_details->class_of_vehicle }}@endif"
                                required>
                            @error('class_of_vehicle')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="taxation_class" class="form-label fw-semibold">9.Taxation Class</label>
                            <input type="text" class="form-control" id="taxation_class" name="taxation_class" value="@if(!$vehicle_details_add){{ $vehicle_details->taxation_class }}@endif" required>
                            @error('taxation_class')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="status_when_registered" class="form-label fw-semibold">10.Status when
                                Registered</label>
                            <input type="text" class="form-control" id="status_when_registered"
                                name="status_when_registered" value="@if(!$vehicle_details_add){{ $vehicle_details->status_when_registered }}@endif" required>
                            @error('status_when_registered')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="fuel_type" class="form-label fw-semibold">11.Fuel Type</label>
                            <input type="text" class="form-control" id="fuel_type" name="fuel_type" value="@if(!$vehicle_details_add){{ $vehicle_details->fuel_type }}@endif" required>
                            @error('fuel_type')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="make" class="form-label fw-semibold">12.Make</label>
                            <input type="text" class="form-control" id="make" name="make" value="@if(!$vehicle_details_add){{ $vehicle_details->make }}@endif" required>
                            @error('make')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="country_of_origin" class="form-label fw-semibold">13.Country of Origin</label>
                            <input type="text" class="form-control" id="country_of_origin" name="country_of_origin" value="@if(!$vehicle_details_add){{ $vehicle_details->country_of_origin }}@endif"
                                required>
                            @error('country_of_origin')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="model" class="form-label fw-semibold">14.Model</label>
                            <input type="text" class="form-control" id="model" name="model" value="@if(!$vehicle_details_add){{ $vehicle_details->model }}@endif" required>
                            @error('model')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="manufactures_description" class="form-label fw-semibold">15.Manufactures
                                Description</label>
                            <input type="text" class="form-control" id="manufactures_description"
                                name="manufactures_description" value="@if(!$vehicle_details_add){{ $vehicle_details->manufactures_description }}@endif" required>
                            @error('manufactures_description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="wheel_base" class="form-label fw-semibold">16.Wheel Base</label>
                            <input type="text" class="form-control" id="wheel_base" name="wheel_base" value="@if(!$vehicle_details_add){{ $vehicle_details->wheel_base }}@endif" required>
                            @error('wheel_base')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="over_hang" class="form-label fw-semibold">17.Over Hang</label>
                            <input type="text" class="form-control" id="over_hang" name="over_hang" value="@if(!$vehicle_details_add){{ $vehicle_details->over_hang }}@endif">
                            @error('over_hang')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="type_of_body" class="form-label fw-semibold">18.Type of Body</label>
                            <input type="text" class="form-control" id="type_of_body" name="type_of_body" value="@if(!$vehicle_details_add){{ $vehicle_details->type_of_body }}@endif" required>
                            @error('type_of_body')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="year_of_manufacture" class="form-label fw-semibold">19.Year of Manufacture</label>
                            <input type="number" class="form-control" id="year_of_manufacture"
                                name="year_of_manufacture" value="@if(!$vehicle_details_add){{ $vehicle_details->year_of_manufacture }}@endif" required>
                            @error('year_of_manufacture')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="colour" class="form-label fw-semibold">20.Colour</label>
                            <input type="text" class="form-control" id="colour" name="colour" value="@if(!$vehicle_details_add){{ $vehicle_details->colour }}@endif" required>
                            @error('colour')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="previous_owners" class="form-label fw-semibold">21.Previous Owners</label>
                            <textarea class="form-control" id="previous_owners" name="previous_owners" rows="10">@if(!$vehicle_details_add){{ $vehicle_details->previous_owners }}@endif</textarea>
                            @error('previous_owners')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="seating_capacity" class="form-label fw-semibold">22.Seating Capacity</label>
                            <input type="text" class="form-control" id="seating_capacity" name="seating_capacity"
                                value="@if(!$vehicle_details_add){{ $vehicle_details->seating_capacity }}@endif" required>
                            @error('seating_capacity')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="weight" class="form-label fw-semibold">23.Weight (Kg)</label>
                            <div class="row" id="weight">
                                <div class="col form-floating">
                                    <input type="text" class="form-control" id="unladen" name="unladen" value="@if(!$vehicle_details_add){{ $vehicle_details->unladen }}@endif" required>
                                    <label for="unladen" class="form-label fw-semibold">Unladen</label>
                                    @error('unladen')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col form-floating">
                                    <input type="text" class="form-control" id="gross" name="gross" value="@if(!$vehicle_details_add){{ $vehicle_details->gross }}@endif">
                                    <label for="gross" class="form-label fw-semibold">Gross</label>
                                    @error('gross')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="tyre_size" class="form-label fw-semibold">24.Tyre Size (cm)</label>
                            <div class="row" id="tyre_size">
                                <div class="col form-floating">
                                    <input type="text" class="form-control" id="front" name="front" value="@if(!$vehicle_details_add){{ $vehicle_details->front }}@endif" required>
                                    <label for="front" class="form-label fw-semibold">Front</label>
                                    @error('front')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col form-floating">
                                    <input type="text" class="form-control" id="rear" name="rear" value="@if(!$vehicle_details_add){{ $vehicle_details->rear }}@endif" required>
                                    <label for="rear" class="form-label fw-semibold">Rear</label>
                                    @error('rear')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col form-floating">
                                    <input type="text" class="form-control" id="dual" name="dual" value="@if(!$vehicle_details_add){{ $vehicle_details->dual }}@endif" required>
                                    <label for="dual" class="form-label fw-semibold">Dual</label>
                                    @error('dual')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col form-floating">
                                    <input type="text" class="form-control" id="single" name="single" value="@if(!$vehicle_details_add){{ $vehicle_details->single }}@endif" required>
                                    <label for="single" class="form-label fw-semibold">Single</label>
                                    @error('single')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="length_width_height" class="form-label fw-semibold">25.Length/Width/Height</label>
                            <input type="text" class="form-control" id="length_width_height"
                                name="length_width_height" value="@if(!$vehicle_details_add){{ $vehicle_details->length_width_height }}@endif" required>
                            @error('length_width_height')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="provincial_council" class="form-label fw-semibold">26.Provincial Council</label>
                            <input type="text" class="form-control" id="provincial_council" name="provincial_council" value="@if(!$vehicle_details_add){{ $vehicle_details->provincial_council }}@endif"
                                required>
                            @error('provincial_council')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="date_of_first_registration" class="form-label fw-semibold">27.Date of First
                                Registration</label>
                            <input type="date" class="form-control" id="date_of_first_registration"
                                name="date_of_first_registration" value="@if(!$vehicle_details_add){{ $vehicle_details->date_of_first_registration }}@endif" required>
                            @error('date_of_first_registration')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="taxes_payable" class="form-label fw-semibold">28.Taxes Payable</label>
                            <input type="text" class="form-control" id="taxes_payable" name="taxes_payable" value="@if(!$vehicle_details_add){{ $vehicle_details->taxes_payable }}@endif">
                            @error('taxes_payable')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn w-100 mt-4
                        @if ($vehicle_details_add)
                            btn-primary
                        @else
                            btn-success text-white
                        @endif">
                        @if ($vehicle_details_add)
                            Register Vehicle
                        @else
                            Update Vehicle
                        @endif
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('dashboard-scripts')

@endpush