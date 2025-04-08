@extends('layouts.dashboard_layout')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <h2 class="h2 my-3 fw-bold">
                    Claim Your Vehicle
                </h2>
                <p class="text-muted">
                    Let's Claim Your Vehicle 👋
                </p>
                <p class="text-muted">To Claim your vehicle, please fill your current Ownership Details as in your Vehicle Registration Certificate.</p>
            </div>

            <div class="p-4 rounded-4">
                <form action="{{ route('dashboard.vehicle.assignVehicleToUser', $validated) }}" method="post" autocomplete="true"
                    aria-autocomplete="true">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label for="current_owner_address_idNo" class="form-label fw-semibold">Current Owner/Address/I.D.No</label>
                            <textarea class="form-control" id="current_owner_address_idNo" name="current_owner_address_idNo" rows="3"></textarea>
                            @error('current_owner_address_idNo')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn w-100 mt-4 btn-primary">Claim My Vehicle</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('dashboard-scripts')
@endpush
