@extends('layouts.auth_layout')

@section('auth-content')
    <div class="card border-0 shadow-lg rounded-4 bg-light">
        <div class="card-body p-4 text-center">
            {{-- Logo --}}
            <div class="text-center mb-4">
                <img src="/motora-logo-4.png" alt="motora-logo" width="180">
                <h2 class="h2 my-3 fw-bold">Email Verification Required</h2>
                <p class="text-muted">We’ve sent a verification link to your email address. Please click the link to
                    verify your account. 👋</p>
            </div>

            @if (session('message'))
                <div class="alert alert-success" role="alert">
                    {{ session('message') }}
                </div>
            @endif

            {{-- Resend Verification Email --}}
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-primary btn-lg w-100 my-3">Resend Verification Email</button>
            </form>

            <p class="text-muted">Didn't receive the email? Check your spam folder or try resending the verification
                link.</p>
        </div>
    </div>
@endsection

@push('auth-scripts')
    <script>
        function checkEmailVerification() {
            fetch('/check-email-verification-status')
            .then(response => response.json())
            .then(data => {
                if (data.verified){
                    window.location.href = '/dashboard';
                }
            });
        }

        setInterval(checkEmailVerification, 3000);

        document.addEventListener('visibilitychange', function() {
            if (document.visibilityState === 'visible') {
                checkEmailVerification();
            }
        });
    </script>
@endpush