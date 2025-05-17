@extends('layouts.dashboard_layout')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4 text-center">
            <div class="text-center mb-4">
                <h2 class="h2 my-3 fw-bold">
                    Manage Vehicle Details
                </h2>
                <p class="text-muted">
                    Let's Manage Vehicles 👋
                </p>
            </div>
            @if (!empty($vehicleDetails))

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle shadow-sm rounded-3 overflow-hidden">
                        <thead class="table-dark align-middle">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-start">Registration Number</th>
                                <th class="text-start">Chassis Number</th>
                                <th class="text-start">Current Owner/Address/I.D.No</th>
                                <th class="text-start">Conditions/Special Notes</th>
                                <th class="text-start">Absolute Owner</th>
                                <th class="text-center">Engine No</th>
                                <th class="text-center">Cylinder Capacity (CC)</th>
                                <th class="text-center">Class of Vehicle</th>
                                <th class="text-center">Taxation Class</th>
                                <th class="text-center">Status when Registered</th>
                                <th class="text-center">Fuel Type</th>
                                <th class="text-center">Make</th>
                                <th class="text-center">Country of Origin</th>
                                <th class="text-center">Model</th>
                                <th class="text-center">Manufactures Description</th>
                                <th class="text-center">Wheel Base</th>
                                <th class="text-center">Over Hang</th>
                                <th class="text-center">Type of Body</th>
                                <th class="text-center">Year of Manufacture</th>
                                <th class="text-center">Colour</th>
                                <th class="text-start">Previous Owners</th>
                                <th class="text-center">Seating Capacity</th>
                                <th class="text-center">Weight (Kg) - Unladen</th>
                                <th class="text-center">Weight (Kg) - Gross</th>
                                <th class="text-center">Tyre Size (cm) - Front</th>
                                <th class="text-center">Tyre Size (cm) - Rear</th>
                                <th class="text-center">Tyre Size (cm) - Dual</th>
                                <th class="text-center">Tyre Size (cm) - Single</th>
                                <th class="text-center">Length/Width/Height</th>
                                <th class="text-center">Provincial Council</th>
                                <th class="text-center">Date of First Registration</th>
                                <th class="text-center">Taxes Payable</th>
                                @if ((Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isDepartmentOfMotorTraffic()) || Auth::guard('web')->check() && Auth::guard('web')->user())
                                    <th class="text-center">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($vehicleDetails as $vehicleDetail)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-start">{{ $vehicleDetail->registration_number }}</td>
                                    <td class="text-start">{{ $vehicleDetail->chassis_number }}</td>
                                    <td class="text-start">{{ $vehicleDetail->current_owner_address_idNo }}</td>
                                    <td class="text-start">{{ $vehicleDetail->conditions_special_notes }}</td>
                                    <td class="text-start">{{ $vehicleDetail->absolute_owner }}</td>
                                    <td class="text-center">{{ $vehicleDetail->engine_no }}</td>
                                    <td class="text-center">{{ $vehicleDetail->cylinder_capacity }}</td>
                                    <td class="text-center">{{ $vehicleDetail->class_of_vehicle }}</td>
                                    <td class="text-center">{{ $vehicleDetail->taxation_class }}</td>
                                    <td class="text-center">{{ $vehicleDetail->status_when_registered }}</td>
                                    <td class="text-center">{{ $vehicleDetail->fuel_type }}</td>
                                    <td class="text-center">{{ $vehicleDetail->make }}</td>
                                    <td class="text-center">{{ $vehicleDetail->country_of_origin }}</td>
                                    <td class="text-center">{{ $vehicleDetail->model }}</td>
                                    <td class="text-center">{{ $vehicleDetail->manufactures_description }}</td>
                                    <td class="text-center">{{ $vehicleDetail->wheel_base }}</td>
                                    <td class="text-center">{{ $vehicleDetail->over_hang }}</td>
                                    <td class="text-center">{{ $vehicleDetail->type_of_body }}</td>
                                    <td class="text-center">{{ $vehicleDetail->year_of_manufacture }}</td>
                                    <td class="text-center">{{ $vehicleDetail->colour }}</td>
                                    <td class="text-start">
                                        @if (!isset($vehicleDetail->previous_owners))
                                            {{ '-' }}
                                        @else
                                            @foreach (is_array(json_decode($vehicleDetail->previous_owners)) ? json_decode($vehicleDetail->previous_owners) : [] as $owner)
                                                <li type="1">{{ $owner }}</li>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $vehicleDetail->seating_capacity }}</td>
                                    <td class="text-center">{{ $vehicleDetail->unladen }}</td>
                                    <td class="text-center">{{ isset($vehicleDetail->gross) ? $vehicleDetail->gross : '-' }}</td>
                                    <td class="text-center">{{ $vehicleDetail->front }}</td>
                                    <td class="text-center">{{ $vehicleDetail->rear }}</td>
                                    <td class="text-center">{{ $vehicleDetail->dual }}</td>
                                    <td class="text-center">{{ $vehicleDetail->single }}</td>
                                    <td class="text-center">{{ $vehicleDetail->length_width_height }}</td>
                                    <td class="text-center">{{ $vehicleDetail->provincial_council }}</td>
                                    <td class="text-center">{{ $vehicleDetail->date_of_first_registration }}</td>
                                    <td class="text-center">{{ isset($vehicleDetail->taxes_payable) ? $vehicleDetail->taxes_payable : '-' }}</td>
                                @if ((Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isDepartmentOfMotorTraffic()) || Auth::guard('web')->check() && Auth::guard('web')->user())
                                        <td class="text-center">
                                            @if((Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isDepartmentOfMotorTraffic()))
                                                <a class="btn btn-primary m-2"
                                                    href="{{ route('dashboard.editVehicleDetails', $vehicleDetail->id) }}">
                                                    <i class="fi fi-rr-pencil"></i> Edit
                                                </a>
                                            @else
                                                {{-- @dd($vehicleDetail) --}}
                                                <a class="btn btn-danger text-white m-2"
                                                    href="{{ route('dashboard.vehicle.unassignVehicleFromUser', $vehicleDetail->id) }}"
                                                    >
                                                    <i class="fi fi-rr-trash"></i> Unassign
                                                </a>
                                            @endif
                                            {{-- <a class="btn text-white @if($vehicleDetail->deleted_at == null) btn-danger @else btn-success  @endif" href="{{ route('dashboard.location.toggle', $vehicleDetail->id) }}">
                                                @if($vehicleDetail->deleted_at == null)<i class="fi fi-rr-trash"></i> Delete @else
                                                <i class="fi fi-rr-trash-restore"></i> Restore @endif
                                            </a> --}}
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center text-muted py-3" colspan="34">No Vehicles Found!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center">
                    <h5 class="text-muted">No Vehicles Found!</h5>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('dashboard-scripts')
@endpush
