<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        @if(isset($title))
            {{ $title }}
        @else
            Motora | Sri Lanka’s First Comprehensive Vehicle History Report
        @endif
    </title>

    @vite(['resources/scss/colors.scss', 'resources/js/app.js'])

</head>

<body>
    @include('components.landing_page.navbar')

    <div class="container">
        <div class="row mt-3  mx-auto">
            <div class="col-md-3"></div>
            @isset($title)
                <div class="col-md-6">
                    <h2 class="h2 text-center">{{ $title }}</h2>
                </div>
            @endisset
            <div class="col-md-3"></div>
        </div>
        <div class="row my-5">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                @yield('auth-content')
            </div>

            <div class="col-md-3"></div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    @stack('auth-scripts')
</body>

</html>
