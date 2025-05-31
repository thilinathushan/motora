@extends('layouts.dashboard_layout')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <h2 class="h2 my-3 fw-bold">
                    Vehicle Faults Prediction Report
                </h2>
                <p class="text-muted">AI powered Vehicle Faults Prediction Report. <i class="fi fi-rr-sparkles"></i></p>
            </div>
            @if (!$result)
                <div class="p-4 rounded-4">

                    <form action="{{ route('dashboard.vehicle.findVehicleForPrediction') }}" method="post" autocomplete="true" aria-autocomplete="true">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="registration_number" class="form-label fw-semibold">1.Registration
                                    Number</label>
                                <input type="text" class="form-control" id="registration_number"
                                    name="registration_number" placeholder="SP ABC 1234"
                                    value="{{ old('registration_number') }}" required>
                                @error('registration_number')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="chassis_number" class="form-label fw-semibold">2.Chassis Number</label>
                                <input type="text" class="form-control" id="chassis_number" name="chassis_number"
                                    value="{{ old('chassis_number') }}" required>
                                @error('chassis_number')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="engine_no" class="form-label fw-semibold">3.Engine No</label>
                                <input type="text" class="form-control" id="engine_no" name="engine_no"
                                    value="{{ old('engine_no') }}" required>
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
                                <form  action="{{ route('dashboard.faultsPredictionReport') }}" method="post">
                                    @csrf

                                    <input type="hidden" name="vehicle_id" value="{{ $result['vehicle_id'] }}">
                                    <input type="hidden" name="registration_number" value="{{ $result['registration_number'] }}">
                                    <input type="hidden" name="chassis_number" value="{{ $result['chassis_number'] }}">
                                    <input type="hidden" name="engine_no" value="{{ $result['engine_no'] }}">

                                    <button class="btn btn-primary" type="submit">Generate Vehicle Faults Prediction Report</button>
                                </form>
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
                            <a href="{{ route('dashboard.faultsPredictionView') }}" class="btn btn-primary">Go to Back</a>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection

@push('dashboard-scripts')
@endpush


