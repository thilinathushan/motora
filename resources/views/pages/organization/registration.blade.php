@extends('layouts.auth_layout')

@section('auth-content')
    <div class="card border-0 shadow-lg rounded-4 bg-light">
        <div class="card-body p-4 text-center">
            {{-- logo --}}
            <div class="text-center mb-4">
                <img src="/motora-logo-4.png" alt="motora-logo" width="180">
                <h2 class="h2 my-3 fw-bold">Super Admin Account Creation</h2>
                <p class="text-muted">Let's get started with your account 👋</p>
            </div>

            <div class="text-start">
                <form action="{{ Route('organization.register_superAdmin') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Name</label>
                        <input type="text" class="form-control form-control-lg" id="name" name="name"
                            placeholder="Name" value="{{ old('name') }}">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
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
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label fw-semibold">Confirm Password</label>
                        <input type="password" class="form-control form-control-lg" id="confirmPassword"
                            name="confirmPassword" placeholder="Confirm Password" minlength="8">
                        <small id="passwordError" class="text-danger d-none">Passwords do not match.</small>
                    </div>

                    <div class="my-4">
                        <button type="submit" id="submitButton" class="btn btn-primary btn-lg w-100">Register Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('auth-scripts')
    <script>
        $(document).ready(function() {
            const $passwordField = $("#password");
            const $confirmPasswordField = $("#confirmPassword");
            const $passwordError = $("#passwordError");
            const $submitButton = $("#submitButton");

            function checkPasswordsMatch() {
                const password = $passwordField.val();
                const confirmPassword = $confirmPasswordField.val();

                if (password !== confirmPassword) {
                    $passwordError.removeClass("d-none");
                    $submitButton.prop("disabled", true);
                } else {
                    $passwordError.addClass("d-none");
                    $submitButton.prop("disabled", false);
                }
            }

            $passwordField.on("input", checkPasswordsMatch);
            $confirmPasswordField.on("input", checkPasswordsMatch);
        });
    </script>
@endpush
