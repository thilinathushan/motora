@extends('layouts.dashboard_layout')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <h2 class="h2 my-3 fw-bold">
                    Change Vehicle Ownership
                </h2>
                <p class="text-muted">Let's Change Vehicle Ownership Details 👋</p>
            </div>

            @if (!$result)
                <div class="p-4 rounded-4">

                    <form action="{{ route('dashboard.vehicle.findUserVehicle') }}" method="post" autocomplete="true" aria-autocomplete="true">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="registration_number" class="form-label fw-semibold">1.Registration
                                    Number</label>
                                <input type="text" class="form-control" id="registration_number"
                                    name="registration_number" placeholder="SP ABC 1234" required>
                                @error('registration_number')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="chassis_number" class="form-label fw-semibold">2.Chassis Number</label>
                                <input type="text" class="form-control" id="chassis_number" name="chassis_number"
                                    required>
                                @error('chassis_number')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="engine_no" class="form-label fw-semibold">3.Engine No</label>
                                <input type="text" class="form-control" id="engine_no" name="engine_no" required>
                                @error('engine_no')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn w-100 mt-4 btn-primary">Find Vehicle</button>
                    </form>
                </div>
            @else
                @if ($result['status'] == 'success')
                    <div class="p-4 rounded-4">
                        <div class="row">
                            <div class="text-center text-muted">Search Result</div>
                            <div class="col-md-12 text-center mt-4">
                                <h4 class="fw-bold">Vehicle Details</h4>
                                <p><strong>Registration Number:</strong> {{ $result['registration_number'] }}</p>
                                <p><strong>Chassis Number:</strong> {{ $result['chassis_number'] }}</p>
                                <p><strong>Engine Number:</strong> {{ $result['engine_no'] }}</p>
                            </div>
                            <div class="col-md-12 text-center mt-5">

                                <a href="{{ route('dashboard.viewVehicleOwnership', $result) }}" class="btn btn-primary">Change Vehicle Ownership</a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-4 rounded-4">
                        <div class="row">
                            <div class="text-center text-muted">Search Result</div>
                            <div class="col-md-12 text-center mt-4">
                                <p class="fw-bold">{{ $result['message'] }}</p>
                            </div>
                        </div>
                        <div class="col-md-12 text-center mt-5">
                            <a href="{{ route('dashboard.findVehicleOwnership') }}" class="btn btn-primary">Go to Back</a>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection

@push('dashboard-scripts')
@endpush


