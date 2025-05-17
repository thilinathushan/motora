@extends('layouts.dashboard_layout')

@section('content')
<div class="row">
    {{-- Card 1 --}}
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm rounded-4 border-0">
            <div class="card-body">
                <h5 class="card-title fw-bold">Total Locations</h5>
                <p class="card-text text-muted">Manage locations effectively.</p>
                <h3 class="fw-bold">15</h3>
            </div>
        </div>
    </div>

    {{-- Card 2 --}}
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm rounded-4 border-0">
            <div class="card-body">
                <h5 class="card-title fw-bold">Total Vehicles</h5>
                <p class="card-text text-muted">Manage Vehicles effectively.</p>
                <h3 class="fw-bold">2,570,945</h3>
            </div>
        </div>
    </div>

    {{-- Card 3 --}}
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm rounded-4 border-0">
            <div class="card-body">
                <h5 class="card-title fw-bold">Ownership Transfers</h5>
                <p class="card-text text-muted">Manage Ownership Transfers effectively.</p>
                <h3 class="fw-bold">752</h3>
            </div>
        </div>
    </div>
</div>

{{-- Responsive Chart Example --}}
{{-- <div class="row">
    <div class="col-12">
        <div class="card shadow-sm rounded-4 border-0">
            <div class="card-body">
                <h5 class="card-title fw-bold">Monthly Statistics</h5>
                <p class="card-text text-muted">Visualize your progress over time.</p>
                <div class="chart-placeholder" style="height: 300px; background: #f8f9fa; border-radius: 8px;"> --}}
                    {{-- Replace this with your chart --}}
                    {{-- <p class="text-center text-muted pt-5">Chart Placeholder</p>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection
