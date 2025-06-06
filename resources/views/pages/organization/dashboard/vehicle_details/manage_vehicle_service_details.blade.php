@extends('layouts.dashboard_layout')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4 text-center">
            <div class="text-center mb-4">
                <h2 class="h2 my-3 fw-bold">
                    Manage Vehicle Service Details
                </h2>
                <p class="text-muted">
                    Let's Manage Vehicle Service Details 👋
                </p>
            </div>
            @if (!empty($vehicleServiceDetails))
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle shadow-sm rounded-3 overflow-hidden">
                        <thead class="table-dark align-middle">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-start">Registration Number</th>
                                <th class="text-start">Current Milage(KM)</th>
                                <th class="text-start">Next Milage(KM)</th>
                                <th class="text-start">Is Engine Oil Change</th>
                                <th class="text-start">Is Engine Oil Filter Change</th>
                                <th class="text-start">Is Brake Oil Change</th>
                                <th class="text-start">Is Brake Pad Change</th>
                                <th class="text-start">Is Transmission Oil Change</th>
                                <th class="text-start">Is Deferential Oil Change</th>
                                <th class="text-start">Is Headlights Okay</th>
                                <th class="text-start">Is Signal Lights Okay</th>
                                <th class="text-start">Is Brake Lights Okay</th>
                                <th class="text-start">Is Air Filter Change</th>
                                <th class="text-start">Is Radiator Oil Change</th>
                                <th class="text-start">Is A/C Filter Change</th>
                                <th class="text-start">A/C Gas Level</th>
                                <th class="text-start">Is Tyre Air Pressure Okay</th>
                                <th class="text-start">Tyre Condition</th>
                                <th class="text-start">Organization</th>
                                <th class="text-start">Location</th>
                                @if (Auth::guard('organization_user')->check() && (Auth::guard('organization_user')->user()->isServiceCenter() &&
                                    !Auth::guard('organization_user')->user()->hasRole('Organization Employee')))
                                    <th class="text-center">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($vehicleServiceDetails as $vehicleServiceDetail)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-start">{{ $vehicleServiceDetail->vehicle_registration_number }}</td>
                                    <td class="text-start">{{ number_format($vehicleServiceDetail->current_milage) }}KM</td>
                                    <td class="text-start">{{ number_format($vehicleServiceDetail->next_service_milage) }}KM</td>
                                    <td class="text-center">{{ ($vehicleServiceDetail->is_engine_oil_change == 'true') ? 'True' : 'False' }}</td>
                                    <td class="text-center">{{ ($vehicleServiceDetail->is_engine_oil_filter_change == 'true') ? 'True' : 'False' }}</td>
                                    <td class="text-center">{{ ($vehicleServiceDetail->is_brake_oil_change == 'true') ? 'True' : 'False' }}</td>
                                    <td class="text-center">{{ ($vehicleServiceDetail->is_brake_pad_change == 'true') ? 'True' : 'False' }}</td>
                                    <td class="text-center">{{ ($vehicleServiceDetail->is_transmission_oil_change == 'true') ? 'True' : 'False' }}</td>
                                    <td class="text-center">{{ ($vehicleServiceDetail->is_deferential_oil_change == 'true') ? 'True' : 'False' }}</td>
                                    <td class="text-center">{{ ($vehicleServiceDetail->is_headlights_okay == 'true') ? 'True' : 'False' }}</td>
                                    <td class="text-center">{{ ($vehicleServiceDetail->is_signal_light_okay == 'true') ? 'True' : 'False' }}</td>
                                    <td class="text-center">{{ ($vehicleServiceDetail->is_brake_lights_okay == 'true') ? 'True' : 'False' }}</td>
                                    <td class="text-center">{{ ($vehicleServiceDetail->is_air_filter_change == 'true') ? 'True' : 'False' }}</td>
                                    <td class="text-center">{{ ($vehicleServiceDetail->is_radiator_oil_change == 'true') ? 'True' : 'False' }}</td>
                                    <td class="text-center">{{ ($vehicleServiceDetail->is_ac_filter_change == 'true') ? 'True' : 'False' }}</td>
                                    <td class="text-center">{{ $vehicleServiceDetail->ac_gas_level }}</td>
                                    <td class="text-center">{{ ($vehicleServiceDetail->is_tyre_air_pressure_ok == 'true') ? 'True' : 'False' }}</td>
                                    <td class="text-center">{{ $vehicleServiceDetail->tyre_condition }}</td>
                                    <td class="text-center">{{ $vehicleServiceDetail->org_name }}</td>
                                    <td class="text-center">{{ $vehicleServiceDetail->loc_name }}</td>
                                    @if (Auth::guard('organization_user')->check() && (Auth::guard('organization_user')->user()->isServiceCenter() &&
                                        !Auth::guard('organization_user')->user()->hasRole('Organization Employee')))
                                        <td class="text-center">
                                            <a class="btn btn-primary m-2"
                                                href="{{ route('dashboard.editVehicleServiceDetails', $vehicleServiceDetail->id) }}"
                                                >
                                                <i class="fi fi-rr-pencil"></i> Edit
                                            </a>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center text-muted py-3" colspan="34">No Vehicle Service Details Found!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center">
                    <h5 class="text-muted">No Vehicle Service Details Found!</h5>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('dashboard-scripts')
@endpush
