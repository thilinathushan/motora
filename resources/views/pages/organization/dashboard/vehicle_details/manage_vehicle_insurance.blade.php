@extends('layouts.dashboard_layout')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4 text-center">
            <div class="text-center mb-4">
                <h2 class="h2 my-3 fw-bold">
                    Manage Vehicle Insurance Details
                </h2>
                <p class="text-muted">
                    Let's Manage Vehicle Insurance Details 👋
                </p>
            </div>
            @if (!empty($vehicleInsuranceDetails))
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle shadow-sm rounded-3 overflow-hidden">
                        <thead class="table-dark align-middle">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-start">Registration Number</th>
                                <th class="text-start">Policy Number</th>
                                <th class="text-start">Valid From</th>
                                <th class="text-start">Valid To</th>
                                <th class="text-start">Organization</th>
                                <th class="text-start">Location</th>
                                @if (Auth::guard('organization_user')->check() && (Auth::guard('organization_user')->user()->isInsuranceCompany() &&
                                    !Auth::guard('organization_user')->user()->hasRole('Organization Employee')))
                                    <th class="text-center">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($vehicleInsuranceDetails as $vehicleInsuranceDetail)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-start">{{ $vehicleInsuranceDetail->vehicle_registration_number }}</td>
                                    <td class="text-start">{{ $vehicleInsuranceDetail->policy_no }}</td>
                                    <td class="text-start">{{ $vehicleInsuranceDetail->valid_from }}</td>
                                    <td class="text-start">{{ $vehicleInsuranceDetail->valid_to }}</td>
                                    <td class="text-start">{{ $vehicleInsuranceDetail->org_name }}</td>
                                    <td class="text-start">{{ $vehicleInsuranceDetail->loc_name }}</td>
                                    @if (Auth::guard('organization_user')->check() && (Auth::guard('organization_user')->user()->isInsuranceCompany() &&
                                        !Auth::guard('organization_user')->user()->hasRole('Organization Employee')))
                                        <td class="text-center">
                                            <a class="btn btn-primary m-2"
                                                href="{{ route('dashboard.editVehicleInsurance', $vehicleInsuranceDetail->id) }}"
                                                >
                                                <i class="fi fi-rr-pencil"></i> Edit
                                            </a>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center text-muted py-3" colspan="34">No Vehicle Insurance Details Found!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center">
                    <h5 class="text-muted">No Vehicle Insurance Details Found!</h5>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('dashboard-scripts')
@endpush
