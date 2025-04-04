@extends('layouts.dashboard_layout')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4 text-center">
            <div class="text-center mb-4">
                <h2 class="h2 my-3 fw-bold">
                    Manage Location Details
                </h2>
                <p class="text-muted">
                    Let's Manage Organization Locations 👋
                </p>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle shadow-sm rounded-3 overflow-hidden">
                    <thead class="table-dark align-middle">
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-start">Registration Number</th>
                            <th class="text-start">Chassis Number</th>
                            <th class="text-start">Current Owner/Address/I.D.No</th>
                            <th class="text-start">Conditions/Special Notes</th>
                            <th class="text-center">Absolute Owner</th>
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
                            <th class="text-center">Previous Owners</th>
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
                            <th class="text-center">Actions</th>
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
                                <td class="text-center">{{ $vehicleDetail->absolute_owner }}</td>
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
                                <td class="text-center">{{ isset($vehicleDetail->previous_owners) ? $vehicleDetail->previous_owners : '-' }}</td>
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
                                <td class="text-center">
                                    <a class="btn btn-primary m-2" href="{{ route('dashboard.editVehicleDetails', $vehicleDetail->id) }}">
                                        <i class="fi fi-rr-pencil"></i> Edit
                                    </a>
                                    {{-- <a class="btn text-white @if($vehicleDetail->deleted_at == null) btn-danger @else btn-success  @endif" href="{{ route('dashboard.location.toggle', $vehicleDetail->id) }}">
                                        @if($vehicleDetail->deleted_at == null)<i class="fi fi-rr-trash"></i> Delete @else
                                        <i class="fi fi-rr-trash-restore"></i> Restore @endif
                                    </a> --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted py-3" colspan="34">No Vehicles Found!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection

@push('dashboard-scripts')
@endpush
