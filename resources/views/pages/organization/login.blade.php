@extends('layouts.auth_layout')

@section('auth-content')
    <div class="card border-0 shadow-lg rounded-4 bg-light">
        <div class="card-body p-4 text-center">
            {{-- logo --}}
            <div class="text-center mb-4">
                <img src="/motora-logo-4.png" alt="motora-logo" width="180">
                <h2 class="h2 my-3 fw-bold">User Login</h2>
                <p class="text-muted">Let's get started with your account 👋</p>
            </div>

            <div class="text-start">
                <form action="{{ Route('login') }}" method="post">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Organizational Email</label>
                        <input type="email" class="form-control form-control-lg" id="email" name="email"
                            placeholder="Email" value="{{ old('email') }}">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <input type="password" class="form-control form-control-lg" id="password" name="password"
                            placeholder="Password" minlength="8">
                    </div>
                    <div class="my-4">
                        <button type="submit" id="submitButton" class="btn btn-primary btn-lg w-100">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('auth-scripts')

@endpush