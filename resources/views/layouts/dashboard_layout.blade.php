<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Uncover the complete story behind any vehicle with a reliable report designed to provide clarity, prevent unexpected issues, and empower confident buying and selling decisions.">
    <meta name="author" content="Motora">

    <title>
        @if (isset($title))
            {{ $title }}
        @endif
        Motora - Sri Lanka’s First Comprehensive Vehicle History Report
    </title>
    <!-- Favicons -->
    <link rel="shortcut icon" href="{{ asset('icons/favicon/favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('icons/favicon/favicon-96x96.png') }}" sizes="96x96">
    <link rel="icon" type="image/svg+xml" href="{{ asset('icons/favicon/favicon.svg') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('icons/favicon/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('icons/favicon/site.webmanifest') }}">
    <meta name="apple-mobile-web-app-title" content="Motora" />

    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}" />
    @livewireStyles
    @vite(['resources/scss/colors.scss', 'resources/js/app.js'])
    <style>
        .icon-hover:hover {
            /* background-color: #36393B; */
            color: #0074e4;
        }
        .icon-hover {
            color: #36393B;
        }
    </style>
</head>
{{-- f5f6fa --}}

<body class="nk-body bg-lighter npc-general has-sidebar">
    <div class="nk-app-root">
        <div class="nk-main">
            @include('components.dashboard.sidenav')

            <div class="nk-wrap">
                @include('components.dashboard.topbar')
                <div class="nk-content">
                    <div class="container-fluid">
                        <div class="nk-content-inner">
                            <div class="nk-content-body">
                                {{-- Session Alerts --}}
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show shadow-sm"
                                        role="alert" id="sessionAlert">
                                        <div class="d-flex align-items-center">
                                            <i class="fi fi-rs-check-circle me-2" style="font-size: 1.2rem;"></i>
                                            <span>{{ session('success') }}</span>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif

                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert"
                                        id="sessionAlert">
                                        <div class="d-flex align-items-center">
                                            <i class="fi fi-rr-exclamation me-2" style="font-size: 1.2rem;"></i>
                                            <span>{{ session('error') }}</span>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif

                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
                @include('components.dashboard.footer')
            </div>
        </div>
    </div>

    @livewireScripts
    <script src="{{ asset('assets/js/bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Automatically dismiss the alert after 5 seconds (5000 ms)
            const alertElement = document.getElementById('sessionAlert');
            if (alertElement) {
                setTimeout(() => {
                    const alert = new bootstrap.Alert(alertElement);
                    alert.close();
                }, 5000); // Time in milliseconds
            }
        });
    </script>

    @stack('dashboard-scripts')
</body>

</html>
