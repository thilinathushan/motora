<div class="nk-sidebar nk-sidebar-fixed is-dark" data-content="sidebarMenu">
    <div class="nk-sidebar-element nk-sidebar-head">
        <div class="nk-menu-trigger">
            <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu">
                <i class="fi fi-rr-arrow-left"></i></a>
            <a href="#" class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex"
                data-target="sidebarMenu"><i class="fi fi-rr-menu-burger"></i></a>
        </div>
        <div class="nk-sidebar-brand">
            <a href="#" class="logo-link nk-sidebar-logo">
                <img class="logo-light logo-img" src="{{ asset('motora-logo-2.png') }}" srcset="" alt="logo" />
                <img class="logo-dark logo-img" src="{{ asset('motora-logo-2.png') }}" srcset="" alt="logo-dark" />
            </a>
        </div>
    </div>
    <!-- .nk-sidebar-element -->

    <div class="nk-sidebar-element nk-sidebar-body">
        <div class="nk-sidebar-content">
            <div class="nk-sidebar-menu" data-simplebar>
                <ul class="nk-menu">
                    <x-dashboard.nav-item href="/dashboard"
                        :active="request()->is('dashboard')"
                        >
                        <x-slot:icon>
                            <i class='fi fi-rr-home'></i>
                        </x-slot:icon>
                        Dashboard
                    </x-dashboard.nav-item>

                    @if (!Auth::guard('web')->user())
                        <x-dashboard.nav-item href="{{ route('dashboard.organization_details') }}"
                            :active="request()->is('dashboard/organization_details')"
                            menuHeading="Organization"
                            >
                            <x-slot:icon>
                                <i class="fi fi-rs-city"></i>
                            </x-slot:icon>
                            Organization Details
                        </x-dashboard.nav-item>

                        <x-dashboard.nav-item href="#"
                            :active="false"
                            menuHeading="Location"
                            :hasSub="true"
                            >
                            <x-slot:icon>
                                <i class="fi fi-rr-marker"></i>
                            </x-slot:icon>
                            Location Details
                            <x-slot:singleNavItem>
                                <x-dashboard.single-nav-item href="{{ route('dashboard.addLocationDetails') }}">Add Location</x-dashboard.single-nav-item>
                                <x-dashboard.single-nav-item href="{{ route('dashboard.manageLocationDetails') }}">Manage Locations</x-dashboard.single-nav-item>
                            </x-slot:singleNavItem>
                        </x-dashboard.nav-item>
                    @endif


                    @if ((Auth::guard('organization_user')->check() && (Auth::guard('organization_user')->user()->isDepartmentOfMotorTraffic())) || Auth::guard('web')->check())
                        <x-dashboard.nav-item href="#"
                            :active="false"
                            menuHeading="Vehicle"
                            :hasSub="true"
                            >
                            <x-slot:icon>
                                <i class="fi fi-rr-car"></i>
                            </x-slot:icon>
                            Vehicle Details
                            <x-slot:singleNavItem>
                                @if (Auth::guard('organization_user')->check() && (Auth::guard('organization_user')->user()->isDepartmentOfMotorTraffic()))
                                    <x-dashboard.single-nav-item href="{{ route('dashboard.addVehicleDetails') }}">Register Vehicle</x-dashboard.single-nav-item>
                                @endif
                                @if (Auth::guard('web')->check())
                                    <x-dashboard.single-nav-item href="{{ route('dashboard.findMyVehicleDetails') }}">Find My Vehicle</x-dashboard.single-nav-item>
                                @endif
                                <x-dashboard.single-nav-item href="{{ route('dashboard.manageVehicleDetails') }}">Manage Vehicles</x-dashboard.single-nav-item>
                            </x-slot:singleNavItem>
                        </x-dashboard.nav-item>
                    @endif

                    @if (Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isDivisionalSecretariat())
                        <x-dashboard.nav-item href="#"
                            :active="false"
                            menuHeading="Vehicle"
                            :hasSub="true"
                            >
                            <x-slot:icon>
                                <i class="fi fi-rr-car"></i>
                            </x-slot:icon>
                            Vehicle Licensing
                            <x-slot:singleNavItem>
                                <x-dashboard.single-nav-item href="{{ route('dashboard.licenseVehicle') }}">License Vehicle</x-dashboard.single-nav-item>
                                <x-dashboard.single-nav-item href="{{ route('dashboard.manageVehicleLicenses') }}">Manage Vehicle License</x-dashboard.single-nav-item>
                            </x-slot:singleNavItem>
                        </x-dashboard.nav-item>
                    @endif

                    @if (Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isEmissionTestCenter())
                        <x-dashboard.nav-item href="#"
                            :active="false"
                            menuHeading="Vehicle"
                            :hasSub="true"
                            >
                            <x-slot:icon>
                                <i class="fi fi-rr-car"></i>
                            </x-slot:icon>
                            Vehicle Emission Details
                            <x-slot:singleNavItem>
                                <x-dashboard.single-nav-item href="{{ route('dashboard.emissionVehicle') }}">Add Vehicle Emission</x-dashboard.single-nav-item>
                                <x-dashboard.single-nav-item href="{{ route('dashboard.manageEmissionVehicle') }}">Manage Vehicle Emission</x-dashboard.single-nav-item>
                            </x-slot:singleNavItem>
                        </x-dashboard.nav-item>
                    @endif

                    @if (Auth::guard('organization_user')->check() && Auth::guard('organization_user')->user()->isServiceCenter())
                        <x-dashboard.nav-item href="#"
                            :active="false"
                            menuHeading="Vehicle"
                            :hasSub="true"
                            >
                            <x-slot:icon>
                                <i class="fi fi-rr-car"></i>
                            </x-slot:icon>
                            Vehicle Service Details
                            <x-slot:singleNavItem>
                                <x-dashboard.single-nav-item href="{{ route('dashboard.vehicleServiceDetails') }}">Add Service Details</x-dashboard.single-nav-item>
                                <x-dashboard.single-nav-item href="{{ route('dashboard.manageVehicleServiceDetails') }}">Manage Service Details</x-dashboard.single-nav-item>
                            </x-slot:singleNavItem>
                        </x-dashboard.nav-item>
                    @endif

                    <x-dashboard.nav-item href="#"
                        :active="false"
                        menuHeading="User"
                        :isNew="true"
                        >
                        <x-slot:icon>
                            <i class="fi fi-rr-circle-user"></i>
                        </x-slot:icon>
                        User Details
                    </x-dashboard.nav-item>

                </ul>
            </div>
        </div>
    </div>
</div>
