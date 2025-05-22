@extends('layouts.dashboard_layout')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <h2 class="h2 my-3 fw-bold">
                    Save Certificate of Registration of Motor Vehicle
                </h2>
                <p class="text-muted">
                    Let's Save Certificate of Registration of Motor Vehicle 👋
                </p>
            </div>
            <div class="d-flex justify-content-center">
                <div class="p-4 rounded-4 col-8 bg-gray-100">
                    <form
                        action="{{ route('dashboard.vehicle.storeVehicleRegCertificate') }}"
                        method="post" autocomplete="true" aria-autocomplete="true" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <h4 class="text-muted text-center mb-4">Vehicle Details</h4>
                            <div class="mb-3">
                                <label for="vehicle_reg_certificate" class="form-label fw-semibold">1.Certificate of Registration of Motor Vehicle</label>
                                <input type="file" accept=".pdf,.jpg,.jpeg,.png" class="form-control" id="vehicle_reg_certificate" name="vehicle_reg_certificate" required>
                                <small class="text-muted">Accepted file types: .pdf, .jpg, .jpeg, .png (Max: 20MB)</small>
                                @error('vehicle_reg_certificate')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <input type="hidden" name="vehicle_id" id="vehicle_id" value="{{ $vehicle_id }}">
                        </div>
                        <button type="submit"
                            class="btn w-100 mt-4 btn-primary">
                                Save Certificate of Registration of Motor Vehicle
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
