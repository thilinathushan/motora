@extends('layouts.dashboard_layout')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4 text-center">
            <div class="text-center mb-4">
                <h2 class="h2 my-3 fw-bold">
                    Manage Vehicle Revenue Licence Details
                </h2>
                <p class="text-muted">
                    Let's Manage Vehicle Revenue Licence Details👋
                </p>
            </div>
            @if (!empty($vehicleLicenses))

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle shadow-sm rounded-3 overflow-hidden">
                        <thead class="table-dark align-middle">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-start">License Date</th>
                                <th class="text-start">License Number</th>
                                <th class="text-start">Vehicle Registration Number</th>
                                <th class="text-start">Class of Vehicle</th>
                                <th class="text-start">Fuel Type</th>
                                <th class="text-center">Vehicle Owner's Name & Address</th>
                                <th class="text-center">Unladen/Gross Weight</th>
                                <th class="text-center">Number of Seats</th>
                                <th class="text-center">Validity Period</th>
                                <th class="text-center">Location</th>
                                @if (Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isDivisionalSecretariat())
                                    <th class="text-center">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($vehicleLicenses as $vehicleLicense)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-start">{{ Carbon\Carbon::parse($vehicleLicense->license_date)->format('Y F') }}</td>
                                    <td class="text-start">{{ $vehicleLicense->license_no }}</td>
                                    <td class="text-start">{{ $vehicleLicense->vehicle_registration_number }}</td>
                                    <td class="text-start">{{ $vehicleLicense->class_of_vehicle }}</td>
                                    <td class="text-start">{{ $vehicleLicense->fuel_type }}</td>
                                    <td class="text-center">{{ $vehicleLicense->current_owner_address_idNo }}</td>
                                    <td class="text-center">{{ $vehicleLicense->unladen }}</td>
                                    <td class="text-center">{{ $vehicleLicense->seating_capacity }}</td>
                                    <td class="text-center">{{ $vehicleLicense->valid_from }} - {{ $vehicleLicense->valid_to }}</td>
                                    <td class="text-center">{{ $vehicleLicense->loc_name }}</td>
                                    @if (Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isDivisionalSecretariat())
                                        <td class="text-center">

                                            <a class="btn btn-primary m-2"
                                                href="{{ route('dashboard.editVehicleLicenses', $vehicleLicense->id) }}">
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
                                    <td class="text-center text-muted py-3" colspan="34">No Vehicles Revenue Licenses Found!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center">
                    <h5 class="text-muted">No Vehicles Revenue Licenses Found!</h5>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('dashboard-scripts')
@endpush
