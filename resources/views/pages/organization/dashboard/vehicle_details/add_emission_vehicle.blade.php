@extends('layouts.dashboard_layout')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <h2 class="h2 my-3 fw-bold">
                    Vehicle Emission
                </h2>
                <p class="text-muted">
                    @if ($addVehicleLEmission)
                        Let's Add your vehicle emission 👋
                    @else
                        Let's Update your vehicle emission 👋
                    @endif
                </p>
            </div>
            <div class="p-4 rounded-4">
                <form
                    action="
                    @if ($addVehicleLEmission)
                        {{ route('dashboard.vehicle.storeVehicleEmissionDetails') }}
                    @else
                        {{ route('dashboard.vehicle.updateVehicleEmissionDetails', [$result['vehicle']->id, $result['vehicleEmission']->odometer]) }}
                    @endif
                    "
                    method="post" autocomplete="true" aria-autocomplete="true">
                    @csrf
                    <div class="row">
                        <h4 class="text-muted mb-4">Vehicle Details</h4>
                        <div class="mb-3 col-md-6">
                            <label for="registration_number" class="form-label fw-semibold">1.Registration Number</label>
                            <input type="text" class="form-control" id="registration_number" name="registration_number"
                                value="{{ $result['vehicle']->registration_number }}" readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="class_of_vehicle" class="form-label fw-semibold">2.Vehicle Class</label>
                            <input type="text" class="form-control" id="class_of_vehicle" name="class_of_vehicle"
                                value="{{ $result['vehicle']->class_of_vehicle }}" readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="chassis_number" class="form-label fw-semibold">3.Chassis Number</label>
                            <input type="text" class="form-control" id="chassis_number" name="chassis_number"
                                value="{{ $result['vehicle']->chassis_number }}" readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="engine_no" class="form-label fw-semibold">4.Engine Number</label>
                            <input type="text" class="form-control" id="engine_no" name="engine_no"
                                value="{{ $result['vehicle']->engine_no }}" readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="date_of_issue" class="form-label fw-semibold">5.Date of Issue</label>
                            <input type="text" class="form-control" id="date_of_issue" name="date_of_issue"
                                value="{{ date('Y-m-d') }}" readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="make" class="form-label fw-semibold">6.Make</label>
                            <input type="text" class="form-control" id="make" name="make"
                                value="{{ $result['vehicle']->make }}" readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="model" class="form-label fw-semibold">7.Model</label>
                            <input type="text" class="form-control" id="model" name="model"
                                value="{{ $result['vehicle']->model }}" readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="year_of_manufacture" class="form-label fw-semibold">8.Year of Manufacture</label>
                            <input type="text" class="form-control" id="year_of_manufacture" name="year_of_manufacture"
                                value="{{ $result['vehicle']->year_of_manufacture }}" readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="fuel_type" class="form-label fw-semibold">9.Fuel Type</label>
                            <input type="text" class="form-control" id="fuel_type" name="fuel_type"
                                value="{{ $result['vehicle']->fuel_type }}" readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="odometer" class="form-label fw-semibold">10.Odometer</label>
                            <input type="number" class="form-control" id="odometer" name="odometer"
                                @if(!$addVehicleLEmission) value="{{ $result['vehicleEmission']->odometer }}"@endif
                                required>
                            @error('odometer')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <hr class="my-5" />
                        <h4 class="text-muted mb-4">Test Details</h4>

                        <div class="mb-3 col-md-6">
                            <label for="emission_test_organization" class="form-label fw-semibold">11.Emission Test
                                Organization</label>
                            <input type="text" class="form-control" id="emission_test_organization"
                                name="emission_test_organization" value="{{ $result['org_details']->org_name }}"
                                readonly>
                            <input type="hidden" class="form-control" id="emission_test_organization_id"
                                name="emission_test_organization_id" value="{{ $result['org_details']->org_id }}"
                                readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="emission_test_center" class="form-label fw-semibold">12.Emission Test
                                Center</label>
                            <input type="text" class="form-control" id="emission_test_center"
                                name="emission_test_center" value="{{ $result['org_details']->loc_name }}" readonly>
                            <input type="hidden" class="form-control" id="emission_test_center_id"
                                name="emission_test_center_id" value="{{ $result['org_details']->loc_id }}"
                                readonly>
                        </div>

                        @if (isset($result['vehicle']->fuel_type) && $result['vehicle']->fuel_type === 'PETROL')
                            <div class="table-responsive">
                                <table class="table border table-bordered table-striped">
                                    <tbody>
                                        <tr>
                                            <td class="text-start"></td>
                                            <td class="text-center">RPM</td>
                                            <td class="text-center">HC</td>
                                            <td class="text-center">CO</td>
                                            <td rowspan="2" class="text-center">OVERALL STATUS</td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">UNIT</td>
                                            <td class="text-center">r/Min</td>
                                            <td class="text-center">ppm v/v</td>
                                            <td class="text-center">% v/v</td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">IDEL</td>
                                            <td class="text-center">
                                                <input type="number" class="form-control" id="rpm_idle"
                                                    name="rpm_idle" step="0.01" @if(!$addVehicleLEmission) value="{{ $result['vehicleEmission']->rpm_idle }}"@endif
                                                    required>
                                                @error('rpm_idle')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </td>
                                            <td class="text-center">
                                                <input type="number" class="form-control" id="hc_idle" name="hc_idle"
                                                    step="0.01" @if(!$addVehicleLEmission) value="{{ $result['vehicleEmission']->hc_idle }}"@endif required>
                                                @error('hc_idle')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </td>
                                            <td class="text-center">
                                                <input type="number" class="form-control" id="co_idle" name="co_idle"
                                                    step="0.01" @if(!$addVehicleLEmission) value="{{ $result['vehicleEmission']->co_idle }}"@endif required>
                                                @error('co_idle')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </td>
                                            <td rowspan="2" class="text-center align-middle">
                                                <select class="form-control" id="overall_status" name="overall_status"
                                                    required>
                                                    @if($addVehicleLEmission)<option value="" selected>-Select-</option>@endif
                                                    <option @if(!$addVehicleLEmission && $result['vehicleEmission']->overall_status == 'Pass') selected  @endif value="Pass" >Pass</option>
                                                    <option @if(!$addVehicleLEmission && $result['vehicleEmission']->overall_status == 'Fail') selected  @endif value="Fail">Fail</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">2500 RPM</td>
                                            <td class="text-center">
                                                <input type="number" class="form-control" id="rpm_2500"
                                                    name="rpm_2500" step="0.01"
                                                    @if(!$addVehicleLEmission) value="{{ $result['vehicleEmission']->rpm_2500 }}"@endif  required>
                                                @error('rpm_2500')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </td>
                                            <td class="text-center">
                                                <input type="number" class="form-control" id="hc_2500" name="hc_2500"
                                                    step="0.01"
                                                    @if(!$addVehicleLEmission) value="{{ $result['vehicleEmission']->hc_2500 }}"@endif  required>
                                                @error('hc_2500')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </td>
                                            <td class="text-center">
                                                <input type="number" class="form-control" id="co_2500" name="co_2500"
                                                    step="0.01"
                                                    @if(!$addVehicleLEmission) value="{{ $result['vehicleEmission']->co_2500 }}"@endif  required>
                                                @error('co_2500')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        @if (isset($result['vehicle']->fuel_type) && $result['vehicle']->fuel_type === 'DIESEL')
                            <div class="table-responsive">
                                <table class="table border table-bordered table-striped">
                                    <tbody>
                                        <tr>
                                            <td class="text-start"></td>
                                            <td class="text-center">SMOKE DENSITY (K) (1/m)</td>
                                            <td class="text-center">OVERALL RESULT</td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">AVERAGE</td>
                                            <td class="text-center">
                                                <input type="number" class="form-control" id="average_k_factor"
                                                    name="average_k_factor" step="0.01" value="@if(!$addVehicleLEmission) {{ $result['vehicleEmission']->average_k_factor }} @endif" required>
                                                @error('average_k_factor')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </td>
                                            <td class="text-center">
                                                <select class="form-control" id="overall_status" name="overall_status"
                                                    required>
                                                    @if($addVehicleLEmission)<option value="" selected>-Select-</option>@endif
                                                    <option @if(!$addVehicleLEmission && $result['vehicleEmission']->overall_status == 'Pass') selected  @endif value="Pass">Pass</option>
                                                    <option @if(!$addVehicleLEmission && $result['vehicleEmission']->overall_status == 'Fail') selected  @endif value="Fail">Fail</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        <input type="hidden" name="vehicle_id" id="vehicle_id" value="{{ $result['vehicle']->id }}">
                    </div>

                    <button type="submit" class="btn w-100 mt-4
                        @if ($addVehicleLEmission)
                            btn-primary
                        @else
                            btn-success text-white
                        @endif
                    ">
                        @if ($addVehicleLEmission)
                            Create Vehicle Emission
                        @else
                            Update Vehicle Emission
                        @endif
                        
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
