<nav class="navbar navbar-expand-lg bg-white sticky-top">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img src="/motora-logo-4.png" alt="motora-logo" width="120">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
            <ul class="navbar-nav me-3 mb-2 mb-lg-0 ">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Prices</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Services
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Essential Vehicle Info</a></li>
                        <li><a class="dropdown-item" href="#">Comprehensive Vehicle History</a></li>
                        <li><a class="dropdown-item" href="#">Maintenance Made Clear</a></li>
                        <li><a class="dropdown-item" href="#">Future-Ready Insights</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        For Organizations
                    </a>
                    <ul class="dropdown-menu">
                        @php
                            use App\Models\OrganizationCategory;
                            $organizationCategories = OrganizationCategory::all();
                        @endphp
                        @if (isset($organizationCategories))
                            @foreach ($organizationCategories as $oranizationCategory)
                                <li><a class="dropdown-item" href="{{ Route('organization.register', $oranizationCategory->id) }}">{{ $oranizationCategory->name }}</a></li>
                            @endforeach
                        @endif
                    </ul>
                </li>
            </ul>
            @guest
                <div class="d-grid gap-2 d-md-block">
                    <a href="{{ route('organization.login_view') }}"><button type="button" class="btn btn-outline-primary me-2">Sign In</button></a>
                    <a href="{{ route('user.user_registration') }}"><button type="button" class="btn btn-primary">Sign Up</button></a>
                </div>
            @endguest

            @auth
                <div class="d-grid gap-2 d-md-block">
                    <form action="{{ route('organization.logout') }}" method="post">
                        @csrf
                    <button type="submit" class="btn btn-outline-primary me-2">Sign Out</button></form>
                </div>
            @endauth
        </div>
    </div>
</nav>
