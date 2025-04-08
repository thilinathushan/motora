@extends('layouts.dashboard_layout')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4 text-center">
            <div class="text-center mb-4">
                <h2 class="h2 my-3 fw-bold">
                    Manage Vehicle Emission Details
                </h2>
                <p class="text-muted">
                    Let's Manage Vehicle Emission Details👋
                </p>
            </div>
            @if (!empty($vehicleEmissions))

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle shadow-sm rounded-3 overflow-hidden">
                        <thead class="table-dark align-middle">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Emission Test Center</th>
                                <th class="text-start">Registration Number</th>
                                <th class="text-start">Vehicle Class</th>
                                <th class="text-start">Chassis Number</th>
                                <th class="text-start">Engine Number</th>
                                <th class="text-start">Validity Period</th>
                                <th class="text-center">Make</th>
                                <th class="text-center">Model</th>
                                <th class="text-center">Year of Manufacture</th>
                                <th class="text-center">Fuel Type</th>
                                <th class="text-center">Odometer</th>
                                <th class="text-center">Overall Status</th>
                                @if (Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isEmissionTestCenter())
                                    <th class="text-center">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($vehicleEmissions as $vehicleEmission)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-start">{{ $vehicleEmission->loc_name }}</td>
                                    <td class="text-start">{{ $vehicleEmission->vehicle_registration_number }}</td>
                                    <td class="text-start">{{ $vehicleEmission->class_of_vehicle }}</td>
                                    <td class="text-start">{{ $vehicleEmission->chassis_number }}</td>
                                    <td class="text-start">{{ $vehicleEmission->engine_no }}</td>
                                    <td class="text-start">{{ $vehicleEmission->valid_from }} - {{ $vehicleEmission->valid_to }}</td>
                                    <td class="text-center">{{ $vehicleEmission->make }}</td>
                                    <td class="text-center">{{ $vehicleEmission->model }}</td>
                                    <td class="text-center">{{ $vehicleEmission->year_of_manufacture }}</td>
                                    <td class="text-center">{{ $vehicleEmission->fuel_type }}</td>
                                    <td class="text-center">{{ number_format($vehicleEmission->odometer) }}</td>
                                    <td class="text-center">{{ $vehicleEmission->overall_status }}</td>
                                    @if (Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isEmissionTestCenter())
                                        <td class="text-center">

                                            <a class="btn btn-primary m-2"
                                                href="{{ route('dashboard.editEmissionVehicle', [$vehicleEmission->vehicle_id, $vehicleEmission->odometer]) }}">
                                                <i class="fi fi-rr-pencil"></i> Edit
                                            </a>
                                            {{-- <a class="btn text-white @if ($vehicleDetail->deleted_at == null) btn-danger @else btn-success  @endif" href="{{ route('dashboard.location.toggle', $vehicleDetail->id) }}">
                                                @if ($vehicleDetail->deleted_at == null)<i class="fi fi-rr-trash"></i> Delete @else
                                                <i class="fi fi-rr-trash-restore"></i> Restore @endif
                                            </a> --}}
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center text-muted py-3" colspan="34">No Vehicles Emissions Found!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center">
                    <h5 class="text-muted">No Vehicles Emissions Found!</h5>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('dashboard-scripts')
@endpush
