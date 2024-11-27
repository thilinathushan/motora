<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    @vite(['resources/scss/colors.scss', 'resources/js/app.js'])
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> --}}
    <style>
        @media (min-width: 992px) {
            .hero-section {
                height: 100vh;
            }
        }
    </style>
</head>

<body>
    @include('components.landing_page.navbar')
    @include('components.landing_page.hero-section')
    @include('components.landing_page.simple-steps')
    @include('components.landing_page.our-services')
    @include('components.landing_page.why-choose')
    @include('components.landing_page.customer-say')
    @include('components.landing_page.footer')

    
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> --}}
</body>

</html>
