@extends('layouts.dashboard_layout')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <h2 class="h2 my-3 fw-bold">
                    Vehicle Service Details
                </h2>
                <p class="text-muted">
                    @if ($addVehicleService)
                        Let's Add your Vehicle Service Details 👋
                    @else
                        Let's Update your Vehicle Service Details 👋
                    @endif
                </p>
            </div>
            <div class="p-4 rounded-4">
                <form
                    action="
                    @if ($addVehicleService)
                        {{ route('dashboard.vehicleService.store') }}
                    @else
                        {{ route('dashboard.vehicleService.update', $vehicleDetails['id']) }}
                    @endif
                    "
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
                        <input type="hidden" name="vehicle_id" id="vehicle_id" value="{{ $vehicleDetails['vehicle_id'] }}">
                    </div>
                    <hr class="py-3 my-3" />
                    <div class="row">
                        <h4 class="text-muted mb-4">Milage Details</h4>
                        <div class="mb-3 col-md-6">
                            <label for="current_milage" class="form-label fw-semibold">4.Current Milage(KM)</label>
                            <input type="number" class="form-control" id="current_milage" name="current_milage"
                                @if (!$addVehicleService) value="{{ $vehicleDetails['current_milage'] }}" @endif
                                required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="next_service_milage" class="form-label fw-semibold">5.Next Service (After
                                KM)</label>
                            <input type="number" class="form-control" id="next_service_milage" name="next_service_milage"
                                @if (!$addVehicleService) value="{{ $vehicleDetails['next_service_milage'] - $vehicleDetails['current_milage'] }}" @endif
                                required>
                        </div>
                    </div>
                    <hr class="py-3 my-3" />
                    <div class="row">
                        <h4 class="text-muted mb-4">Service Details</h4>
                        <div class="mb-3 col-md-3">
                            <label class="form-label fw-semibold">6. Is Engine Oil Change</label>
                            <button type="button" class="form-control btn btn-outline-secondary toggle-select-btn"
                                data-target="is_engine_oil_change" autocomplete="off">
                                <i class="fi fi-rr-cross"></i> No
                            </button>
                            <input type="hidden" name="is_engine_oil_change" id="is_engine_oil_change"
                                @if (!$addVehicleService) value="{{ $vehicleDetails['is_engine_oil_change'] }}" @else value="false" @endif>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label fw-semibold">7. Is Engine Oil Filter Change</label>
                            <button type="button" class="form-control btn btn-outline-secondary toggle-select-btn"
                                data-target="is_engine_oil_filter_change" autocomplete="off">
                                <i class="fi fi-rr-cross"></i> No
                            </button>
                            <input type="hidden" name="is_engine_oil_filter_change" id="is_engine_oil_filter_change"
                                @if (!$addVehicleService) value="{{ $vehicleDetails['is_engine_oil_filter_change'] }}" @else value="false" @endif>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label fw-semibold">8. Is Brake Oil Change</label>
                            <button type="button" class="form-control btn btn-outline-secondary toggle-select-btn"
                                data-target="is_brake_oil_change" autocomplete="off">
                                <i class="fi fi-rr-cross"></i> No
                            </button>
                            <input type="hidden" name="is_brake_oil_change" id="is_brake_oil_change" @if (!$addVehicleService) value="{{ $vehicleDetails['is_brake_oil_change'] }}" @else value="false" @endif>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label fw-semibold">9. Is Brake Pad Change</label>
                            <button type="button" class="form-control btn btn-outline-secondary toggle-select-btn"
                                data-target="is_brake_pad_change" autocomplete="off">
                                <i class="fi fi-rr-cross"></i> No
                            </button>
                            <input type="hidden" name="is_brake_pad_change" id="is_brake_pad_change" @if (!$addVehicleService) value="{{ $vehicleDetails['is_brake_pad_change'] }}" @else value="false" @endif>
                        </div>
                    </div>

                    <hr class="py-3 my-3" />
                    <div class="row">
                        <div class="mb-3 col-md-3">
                            <label class="form-label fw-semibold">10. Is Transmission Oil Change</label>
                            <button type="button" class="form-control btn btn-outline-secondary toggle-select-btn"
                                data-target="is_transmission_oil_change" autocomplete="off">
                                <i class="fi fi-rr-cross"></i> No
                            </button>
                            <input type="hidden" name="is_transmission_oil_change" id="is_transmission_oil_change"
                                @if (!$addVehicleService) value="{{ $vehicleDetails['is_transmission_oil_change'] }}" @else value="false" @endif>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label fw-semibold">11. Is Deferential Oil Change</label>
                            <button type="button" class="form-control btn btn-outline-secondary toggle-select-btn"
                                data-target="is_deferential_oil_change" autocomplete="off">
                                <i class="fi fi-rr-cross"></i> No
                            </button>
                            <input type="hidden" name="is_deferential_oil_change" id="is_deferential_oil_change"
                                @if (!$addVehicleService) value="{{ $vehicleDetails['is_deferential_oil_change'] }}" @else value="false" @endif>
                        </div>

                    </div>

                    <hr class="py-3 my-3" />
                    <div class="row">
                        <div class="mb-3 col-md-3">
                            <label class="form-label fw-semibold">12. Is Headlights Okay</label>
                            <button type="button" class="form-control btn btn-outline-secondary toggle-select-btn"
                                data-target="is_headlights_okay" autocomplete="off">
                                <i class="fi fi-rr-cross"></i> No
                            </button>
                            <input type="hidden" name="is_headlights_okay" id="is_headlights_okay" @if (!$addVehicleService) value="{{ $vehicleDetails['is_headlights_okay'] }}" @else value="false" @endif>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label fw-semibold">13. Is Signal Lights Okay</label>
                            <button type="button" class="form-control btn btn-outline-secondary toggle-select-btn"
                                data-target="is_signal_light_okay" autocomplete="off">
                                <i class="fi fi-rr-cross"></i> No
                            </button>
                            <input type="hidden" name="is_signal_light_okay" id="is_signal_light_okay" @if (!$addVehicleService) value="{{ $vehicleDetails['is_signal_light_okay'] }}" @else value="false" @endif>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label fw-semibold">14. Is Brake Lights Okay</label>
                            <button type="button" class="form-control btn btn-outline-secondary toggle-select-btn"
                                data-target="is_brake_lights_okay" autocomplete="off">
                                <i class="fi fi-rr-cross"></i> No
                            </button>
                            <input type="hidden" name="is_brake_lights_okay" id="is_brake_lights_okay" @if (!$addVehicleService) value="{{ $vehicleDetails['is_brake_lights_okay'] }}" @else value="false" @endif>
                        </div>
                    </div>
                    <hr class="py-3 my-3" />
                    <div class="row">
                        <div class="mb-3 col-md-3">
                            <label class="form-label fw-semibold">15. Is Air Filter Change</label>
                            <button type="button" class="form-control btn btn-outline-secondary toggle-select-btn"
                                data-target="is_air_filter_change" autocomplete="off">
                                <i class="fi fi-rr-cross"></i> No
                            </button>
                            <input type="hidden" name="is_air_filter_change" id="is_air_filter_change" @if (!$addVehicleService) value="{{ $vehicleDetails['is_air_filter_change'] }}" @else value="false" @endif>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label fw-semibold">16. Is Radiator Oil Change</label>
                            <button type="button" class="form-control btn btn-outline-secondary toggle-select-btn"
                                data-target="is_radiator_oil_change" autocomplete="off">
                                <i class="fi fi-rr-cross"></i> No
                            </button>
                            <input type="hidden" name="is_radiator_oil_change" id="is_radiator_oil_change"
                                @if (!$addVehicleService) value="{{ $vehicleDetails['is_radiator_oil_change'] }}" @else value="false" @endif>
                        </div>
                    </div>
                    <hr class="py-3 my-3" />
                    <div class="row">
                        <div class="mb-3 col-md-3">
                            <label class="form-label fw-semibold">17. Is A/C Filter Change</label>
                            <button type="button" class="form-control btn btn-outline-secondary toggle-select-btn"
                                data-target="is_ac_filter_change" autocomplete="off">
                                <i class="fi fi-rr-cross"></i> No
                            </button>
                            <input type="hidden" name="is_ac_filter_change" id="is_ac_filter_change" @if (!$addVehicleService) value="{{ $vehicleDetails['is_ac_filter_change'] }}" @else value="false" @endif>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label fw-semibold">18. A/C Gas Level</label>
                            <input type="number" name="ac_gas_level" id="ac_gas_level" class="form-control"
                                @if (!$addVehicleService) value="{{ $vehicleDetails['ac_gas_level'] }}" @endif
                                placeholder="1-5" required min="1" max="5">
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label fw-semibold">19. Is Tyre Air Pressure Okay</label>
                            <button type="button" class="form-control btn btn-outline-secondary toggle-select-btn"
                                data-target="is_tyre_air_pressure_ok" autocomplete="off">
                                <i class="fi fi-rr-cross"></i> No
                            </button>
                            <input type="hidden" name="is_tyre_air_pressure_ok" id="is_tyre_air_pressure_ok"
                                @if (!$addVehicleService) value="{{ $vehicleDetails['is_tyre_air_pressure_ok'] }}" @else value="false" @endif>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label fw-semibold">20. Tyre Condition</label>
                            <input type="number" name="tyre_condition" id="tyre_condition" class="form-control"
                                @if (!$addVehicleService) value="{{ $vehicleDetails['tyre_condition'] }}" @endif
                                placeholder="1-5" required min="1" max="5">
                        </div>
                    </div>


                    <button type="submit"
                        class="btn w-100 mt-4
                        @if ($addVehicleService) btn-primary
                        @else
                            btn-success text-white @endif
                    ">
                        @if ($addVehicleService)
                            Create Vehicle Service Details
                        @else
                            Update Vehicle Service Details
                        @endif
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('dashboard-scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Apply button state based on hidden input values on load
            document.querySelectorAll(".toggle-select-btn").forEach(function(btn) {
                const inputId = btn.getAttribute("data-target");
                const hiddenInput = document.getElementById(inputId);

                updateToggleButton(btn, hiddenInput.value === "true");

                // Add click event listener
                btn.addEventListener("click", function() {
                    const currentValue = hiddenInput.value === "true";
                    hiddenInput.value = !currentValue;
                    updateToggleButton(btn, !currentValue);
                });
            });

            function updateToggleButton(button, isSelected) {
                const icon = button.querySelector("i");
                if (isSelected) {
                    button.classList.remove("btn-outline-secondary");
                    button.classList.add("btn-secondary");
                    icon.className = "fi fi-rr-check";
                    button.innerHTML = '<i class="fi fi-rr-check"></i> Yes';
                } else {
                    button.classList.remove("btn-secondary");
                    button.classList.add("btn-outline-secondary");
                    icon.className = "fi fi-rr-cross";
                    button.innerHTML = '<i class="fi fi-rr-cross"></i> No';
                }
            }
        });
    </script>
@endpush
