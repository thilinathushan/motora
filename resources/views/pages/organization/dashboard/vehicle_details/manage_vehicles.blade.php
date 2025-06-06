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
                                @if (
                                    (Auth::guard('organization_user')->check() &&
                                        Auth::guard('organization_user')->user()->isDepartmentOfMotorTraffic()) ||
                                        (Auth::guard('web')->check() && Auth::guard('web')->user()))
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
                                    <td class="text-center">
                                        {{ isset($vehicleDetail->gross) ? $vehicleDetail->gross : '-' }}</td>
                                    <td class="text-center">{{ $vehicleDetail->front }}</td>
                                    <td class="text-center">{{ $vehicleDetail->rear }}</td>
                                    <td class="text-center">{{ $vehicleDetail->dual }}</td>
                                    <td class="text-center">{{ $vehicleDetail->single }}</td>
                                    <td class="text-center">{{ $vehicleDetail->length_width_height }}</td>
                                    <td class="text-center">{{ $vehicleDetail->provincial_council }}</td>
                                    <td class="text-center">{{ $vehicleDetail->date_of_first_registration }}</td>
                                    <td class="text-center">
                                        {{ isset($vehicleDetail->taxes_payable) ? $vehicleDetail->taxes_payable : '-' }}
                                    </td>
                                    @if (
                                        (Auth::guard('organization_user')->check() &&
                                            Auth::guard('organization_user')->user()->isDepartmentOfMotorTraffic()) ||
                                            (Auth::guard('web')->check() && Auth::guard('web')->user()))
                                        <td class="text-center">
                                            @if (Auth::guard('organization_user')->check() &&
                                                (Auth::guard('organization_user')->user()->isDepartmentOfMotorTraffic() &&
                                                !Auth::guard('organization_user')->user()->hasRole('Organization Employee')))
                                                <a class="btn btn-primary m-2"
                                                    href="{{ route('dashboard.editVehicleDetails', $vehicleDetail->id) }}">
                                                    <i class="fi fi-rr-pencil"></i> Edit
                                                </a>
                                            @endif
                                            @if(Auth::guard('web')->user())
                                                <a class="btn btn-danger text-white m-2"
                                                    href="{{ route('dashboard.vehicle.unassignVehicleFromUser', $vehicleDetail->id) }}">
                                                    <i class="fi fi-rr-trash"></i> Unassign
                                                </a>
                                                @if ($vehicleDetail->verification_score == 4)
                                                    <a class="btn btn-success text-white m-2">
                                                        <i class="fi fi-rr-shield-check text-start"></i><span>100% Verified</span>
                                                    </a>
                                                @else

                                                    <a class="btn btn-warning text-white m-2" data-bs-toggle="modal"
                                                        data-bs-target="#verificationModal-{{ $vehicleDetail->id }}"
                                                        {{-- href="{{ route('dashboard.vehicle.unassignVehicleFromUser', $vehicleDetail->id) }}" --}}>
                                                        <i class="fi fi-rr-shield-check"></i> Request Verification
                                                    </a>

                                                    <!-- Verification Instructions Modal -->
                                                    <div class="modal fade" id="verificationModal-{{ $vehicleDetail->id }}"
                                                        tabindex="-1" aria-labelledby="verificationModalLabel-{{ $vehicleDetail->id }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-warning text-white">
                                                                    <h5 class="modal-title text-center" id="verificationModalLabel-{{ $vehicleDetail->id }}">
                                                                        <i class="fi fi-rr-shield-check"></i> Vehicle Verification Instructions
                                                                    </h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="alert alert-info">
                                                                        <p><strong>To verify this vehicle, you need to have at least one record from each of the following organizations:</strong></p>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="card mb-3">
                                                                                <div class="card-header bg-secondary text-white">
                                                                                    <i class="fi fi-rr-building"></i>
                                                                                    Divisional Secretariat
                                                                                </div>
                                                                                <div class="card-body text-start">
                                                                                    <p>Visit your local Divisional Secretariat office to obtain a revenue license for your vehicle.</p>
                                                                                    <p>Required documents:</p>
                                                                                    <ol class="list-group list-group-numbered">
                                                                                        <li class="list-group-item">Vehicle registration certificate</li>
                                                                                        <li class="list-group-item">Valid insurance certificate</li>
                                                                                        <li class="list-group-item">Emission test certificate</li>
                                                                                        <li class="list-group-item">Proof of identity</li>
                                                                                    </ol>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <div class="card mb-3">
                                                                                <div class="card-header bg-secondary text-white">
                                                                                    <i class="fi fi-rr-smoke"></i> Emission Test Center
                                                                                </div>
                                                                                <div class="card-body text-start">
                                                                                    <p>Get your vehicle's emission levels tested at an authorized emission test center.</p>
                                                                                    <p>Required items:</p>
                                                                                    <ol class="list-group list-group-numbered">
                                                                                        <li class="list-group-item">Vehicle registration certificate</li>
                                                                                        <li class="list-group-item">Vehicle in good running condition</li>
                                                                                        <li class="list-group-item">Proof of identity</li>
                                                                                    </ol>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <div class="card mb-3">
                                                                                <div class="card-header bg-secondary text-white">
                                                                                    <i class="fi fi-rr-shield"></i> Insurance Company
                                                                                </div>
                                                                                <div class="card-body text-start">
                                                                                    <p>Ensure your vehicle has valid insurance coverage from a registered insurance provider.</p>
                                                                                    <p>Required documents:</p>
                                                                                    <ol class="list-group list-group-numbered">
                                                                                        <li class="list-group-item">Vehicle registration certificate</li>
                                                                                        <li class="list-group-item">Previous insurance policy (if any)</li>
                                                                                        <li class="list-group-item">Proof of identity</li>
                                                                                    </ol>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <div class="card mb-3">
                                                                                <div class="card-header bg-secondary text-white">
                                                                                    <i class="fi fi-rr-wrench"></i> Service Center
                                                                                </div>
                                                                                <div class="card-body text-start">
                                                                                    <p>Have your vehicle serviced at an authorized service center to ensure it's in good condition.</p>
                                                                                    <p>Recommended service items:</p>
                                                                                    <ol class="list-group list-group-numbered">
                                                                                        <li class="list-group-item">Oil and filter change</li>
                                                                                        <li class="list-group-item">Brake inspection</li>
                                                                                        <li class="list-group-item">Tire condition check</li>
                                                                                        <li class="list-group-item">General vehicle inspection</li>
                                                                                    </ol>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="alert alert-warning mt-3">
                                                                        <p><strong>Note:</strong> Your vehicle must have at least one record from each of the above organizations to be eligible for verification.</p>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-outline-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                    <form action="{{ route('dashboard.vehicle.verifyVehicleRegistration') }}" method="post">
                                                                        @csrf
                                                                        <input type="hidden" name="vehicle_id" value="{{ $vehicleDetail->id }}">
                                                                        <input type="hidden" name="vehicle_registration_number" value="{{ $vehicleDetail->registration_number }}">
                                                                        <input type="hidden" name="vehicle_chassis_number" value="{{ $vehicleDetail->chassis_number }}">
                                                                        <input type="hidden" name="vehicle_engine_number" value="{{ $vehicleDetail->engine_no }}">

                                                                        <button type="submit" class="btn btn-warning text-white">
                                                                            <i class="fi fi-rr-shield-check"></i> Proceed with Verification
                                                                        </button>
                                                                    </form>
                                                                    {{-- <a @method('post') href="" class="btn btn-warning text-white">
                                                                        <i class="fi fi-rr-shield-check"></i> Proceed with Verification
                                                                    </a> --}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                            {{-- <a class="btn text-white @if ($vehicleDetail->deleted_at == null) btn-danger @else btn-success  @endif" href="{{ route('dashboard.location.toggle', $vehicleDetail->id) }}">
                                                @if ($vehicleDetail->deleted_at == null)<i class="fi fi-rr-trash"></i> Delete @else
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
