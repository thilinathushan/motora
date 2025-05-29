@extends('layouts.dashboard_layout')

@section('content')
<div class="row">
    {{-- Card 1 : for Department of Motor Traffic or General User--}}
    @if ((Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isDepartmentOfMotorTraffic()) || Auth::guard('web')->check())
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Total Vehicles</h5>
                    <p class="card-text text-muted">Manage Vehicles effectively.</p>
                    <h3 class="fw-bold">
                        @if (isset($dashboardStat) && isset($dashboardStat['vehicleCount']))
                            {{ $dashboardStat['vehicleCount'] }}
                        @else
                            0
                        @endif
                    </h3>
                </div>
            </div>
        </div>
    @endif

    {{-- Card 1 : for Divisional Secretary--}}
    @if ((Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isDivisionalSecretariat()))
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Total Vehicle Revenue Licences</h5>
                    <p class="card-text text-muted">Manage Vehicle Revenue Licences effectively.</p>
                    <h3 class="fw-bold">
                        @if (isset($dashboardStat) && isset($dashboardStat['revenueLicenceCount']))
                            {{ $dashboardStat['revenueLicenceCount'] }}
                        @else
                            0
                        @endif
                    </h3>
                </div>
            </div>
        </div>
    @endif

    {{-- Card 1 : for Emission Test Center--}}
    @if ((Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isEmissionTestCenter()))
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Total Vehicle Emission Certificates</h5>
                    <p class="card-text text-muted">Manage Vehicle Emission Certificates effectively.</p>
                    <h3 class="fw-bold">
                        @if (isset($dashboardStat) && isset($dashboardStat['emissionTestCount']))
                            {{ $dashboardStat['emissionTestCount'] }}
                        @else
                            0
                        @endif
                    </h3>
                </div>
            </div>
        </div>
    @endif

    {{-- Card 1 : for Insurance Company--}}
    @if ((Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isInsuranceCompany()))
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Total Vehicle Insurances</h5>
                    <p class="card-text text-muted">Manage Vehicle Insurances effectively.</p>
                    <h3 class="fw-bold">
                        @if (isset($dashboardStat) && isset($dashboardStat['insuranceCount']))
                            {{ $dashboardStat['insuranceCount'] }}
                        @else
                            0
                        @endif
                    </h3>
                </div>
            </div>
        </div>
    @endif

    {{-- Card 1 : for Service Centers--}}
    @if ((Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isServiceCenter()))
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Total Vehicle Services</h5>
                    <p class="card-text text-muted">Manage Vehicle Services effectively.</p>
                    <h3 class="fw-bold">
                        @if (isset($dashboardStat) && isset($dashboardStat['serviceCount']))
                            {{ $dashboardStat['serviceCount'] }}
                        @else
                            0
                        @endif
                    </h3>
                </div>
            </div>
        </div>
    @endif

    @if (Auth::guard('organization_user')->check())
        {{-- Card 2 --}}
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Total Locations</h5>
                    <p class="card-text text-muted">Manage locations effectively.</p>
                    <h3 class="fw-bold">
                        @if (isset($dashboardStat) && isset($dashboardStat['locationCount']))
                            {{ $dashboardStat['locationCount'] }}
                        @else
                            0
                        @endif
                    </h3>
                </div>
            </div>
        </div>
    @endif

    @if (Auth::guard('organization_user')->check())
        {{-- Card 3 --}}
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Total Users</h5>
                    <p class="card-text text-muted">Manage Users effectively.</p>
                    <h3 class="fw-bold">
                        @if (isset($dashboardStat) && isset($dashboardStat['userCount']))
                            {{ $dashboardStat['userCount'] }}
                        @else
                            0
                        @endif
                    </h3>
                </div>
            </div>
        </div>
    @endif
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
