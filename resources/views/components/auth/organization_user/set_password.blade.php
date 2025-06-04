@extends('layouts.auth_layout')

@section('auth-content')
    <div class="card border-0 shadow-lg rounded-4 bg-light">
        <div class="card-body p-4 text-center">
            {{-- Logo --}}
            <div class="text-center mb-4">
                <img src="/motora-logo-4.png" alt="motora-logo" width="180">
                <h2 class="h2 my-3 fw-bold">Set Your Password to Get Started</h2>
                <p class="text-muted">You're almost there! Please create a secure password to activate your account and start using the system. 👋</p>
            </div>

            <div class="text-start">
                <form method="POST" class="row" action="{{ route('organizationUser.setPassword', ['user' => $user->id, 'signature' => request()->signature, 'expires' => request()->expires]) }}">
                    @csrf
                    <div class="mb-3">
                        <label for="pass" class="form-label fw-semibold">New Password</label>
                        <input type="password" id="pass" name="password" class="form-control" minlength="8" required>
                    </div>
                    <div class="mb-3">
                        <label for="con_pass" class="form-label fw-semibold">Confirm Password</label>
                        <input type="password" id="con_pass" name="password_confirmation" class="form-control" minlength="8" required>
                        <small id="passwordError" class="text-danger d-none">Passwords do not match.</small>
                    </div>
                    <button class="btn btn-primary" id="submitButton" type="submit">Set Password</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('auth-scripts')
    <script>
        $(document).ready(function() {
            const $passwordField = $("#pass");
            const $confirmPasswordField = $("#con_pass");
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