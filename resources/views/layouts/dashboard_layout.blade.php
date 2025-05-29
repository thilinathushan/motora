<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Uncover the complete story behind any vehicle with a reliable report designed to provide clarity, prevent unexpected issues, and empower confident buying and selling decisions.">
    <meta name="author" content="Motora">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

        /* reauth styles */
        .step-progress {
            margin: 0 20px;
        }
        .step {
            text-align: center;
            flex: 1;
        }
        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            font-weight: bold;
            border: 2px solid #e9ecef;
            font-size: 16px;
            line-height: 1;
        }
        .step-circle i {
            font-size: 18px;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .step.active .step-circle {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
        .step.completed .step-circle {
            background-color: #28a745;
            color: white;
            border-color: #28a745;
        }
        .step-line {
            height: 2px;
            background-color: #e9ecef;
            margin-top: 19px;
            flex: 1;
            margin-left: 10px;
            margin-right: 10px;
        }
        .step-line.completed {
            background-color: #28a745;
        }
        .step small {
            color: #6c757d;
            font-size: 0.8rem;
        }
        .step.active small {
            color: #007bff;
            font-weight: 600;
        }
        .step.completed small {
            color: #28a745;
            font-weight: 600;
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
    <!-- Include the Livewire modal component -->
    @livewire('reauthentication-modal')

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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if modal should be shown on page load
            @if (session('show_crypto_modal'))
                Livewire.dispatch('show-crypto-modal');
            @endif
        });

        // Listen for form resubmission after crypto verification
        document.addEventListener('livewire:init', () => {
            Livewire.on('schedule-delayed-resubmit', () => {
                // console.log('Scheduling delayed resubmit...');

                // Wait 1.5 seconds to show success message, then proceed
                setTimeout(() => {
                    // console.log('Proceeding with form resubmit and modal close...');

                    // Close modal
                    Livewire.dispatch('closeModal');

                    // Trigger form resubmit
                    Livewire.dispatch('resubmit-form', [{
                        action: '{{ session('pending_form_action') }}',
                        method: '{{ session('pending_form_method') }}',
                        data: @json(session('pending_form_data'))
                    }]);

                }, 1500); // 1.5 second delay to show success message
            });

            Livewire.on('resubmit-form', (event) => {
                const formData = event[0]; // Livewire passes data as array

                if (formData && formData.action && formData.data) {
                    // Create a temporary form and submit it
                    const form = document.createElement('form');
                    form.method = formData.method || 'POST';
                    form.action = formData.action;
                    form.style.display = 'none';

                    // Add all the original form data as hidden inputs
                    Object.keys(formData.data).forEach(key => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = key;
                        input.value = formData.data[key];
                        form.appendChild(input);
                    });

                    // Append to body and submit
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    </script>

    @stack('dashboard-scripts')
</body>

</html>
