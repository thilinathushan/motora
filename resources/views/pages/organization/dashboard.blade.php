{{-- @extends('layouts.auth_layout')

@section('auth-content') --}}
    <div class="card border-0 shadow-lg rounded-4 bg-light">
        <div class="card-body p-4 text-center">
            {{-- logo --}}
            <div class="text-center mb-4">
                <img src="/motora-logo-4.png" alt="motora-logo" width="180">
                <h2 class="h2 my-3 fw-bold">Super Admin Dashboard</h2>
                <p class="text-muted">Let's get started with your account 👋</p>
            </div>
            <div class="text-start">
                <h3 class="h3 mb-3">Welcome, {{ Auth::guard('organization_user')->user()->name }}</h3>
                <p class="text-muted">You are logged in as a super admin.</p>
                {{-- form logout --}}
                <form action="{{ Route('organization.logout') }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-lg w-100">Logout</button>
                </form>
            </div>
        </div>
    </div>
{{-- @endsection --}}