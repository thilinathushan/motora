@extends('layouts.dashboard_layout')

@section('content')
<div class="row">
    {{-- Card 1 --}}
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm rounded-4 border-0">
            <div class="card-body">
                <h5 class="card-title fw-bold">Total Users</h5>
                <p class="card-text text-muted">Manage your user base effectively.</p>
                <h3 class="fw-bold">1,245</h3>
            </div>
        </div>
    </div>

    {{-- Card 2 --}}
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm rounded-4 border-0">
            <div class="card-body">
                <h5 class="card-title fw-bold">Revenue</h5>
                <p class="card-text text-muted">Track your monthly earnings.</p>
                <h3 class="fw-bold">$12,450</h3>
            </div>
        </div>
    </div>

    {{-- Card 3 --}}
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm rounded-4 border-0">
            <div class="card-body">
                <h5 class="card-title fw-bold">Pending Tasks</h5>
                <p class="card-text text-muted">Stay on top of your workload.</p>
                <h3 class="fw-bold">7</h3>
            </div>
        </div>
    </div>
</div>

{{-- Responsive Chart Example --}}
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm rounded-4 border-0">
            <div class="card-body">
                <h5 class="card-title fw-bold">Monthly Statistics</h5>
                <p class="card-text text-muted">Visualize your progress over time.</p>
                <div class="chart-placeholder" style="height: 300px; background: #f8f9fa; border-radius: 8px;">
                    {{-- Replace this with your chart --}}
                    <p class="text-center text-muted pt-5">Chart Placeholder</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
