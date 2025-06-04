@extends('layouts.dashboard_layout')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <h2 class="h2 my-3 fw-bold">
                    @if ($addUser)
                        Create New User
                    @else
                        Update User
                    @endif
                </h2>
                <p class="text-muted">
                    @if ($addUser)
                        Let's Create New User Details 👋
                    @else
                        Let's Update User Details 👋
                    @endif
                </p>
            </div>
            <div class="p-2 rounded-4">
                <form
                    action="
                    @if ($addUser)
                        {{ route('dashboard.organizationUser.store') }}
                    @else
                        {{ route('dashboard.organizationUser.update', $organizationUser->id) }}
                    @endif
                    "
                    method="post" autocomplete="true" aria-autocomplete="true">
                    @csrf
                    <div class="row">
                        <h4 class="text-muted mb-4">User Details</h4>
                        <div class="mb-3 col-md-6">
                            <label for="org_user_name" class="form-label fw-semibold">1.Name</label>
                            <input type="text" class="form-control" id="org_user_name" name="org_user_name"
                                @if(!$addUser)value="{{ $organizationUser->name }}"@endif required>
                            @error('org_user_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="org_user_email" class="form-label fw-semibold">2.Email</label>
                            <input type="email" class="form-control" id="org_user_email" name="org_user_email"
                                @if(!$addUser)value="{{ $organizationUser->email }}"@endif required>
                            @error('org_user_email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="org_user_type_id" class="form-label fw-semibold">3.User Type</label>
                            <select class="form-select" name="org_user_type_id" id="org_user_type_id" required>
                                @if(!$addUser)<option>Select User Type</option>@endif
                                @foreach ($userTypes as $userType)
                                    <option value="{{ $userType->id }}"
                                        @if(!$addUser && ($organizationUser->u_tp_id == $userType->id))
                                            selected
                                        @endif
                                    >{{ $userType->name }}</option>
                                @endforeach
                            </select>
                            @error('org_user_type_id')
                                <span class="text-danger">Select a User Type</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="org_user_loc_id" class="form-label fw-semibold">4.Location</label>
                            <select class="form-select" name="org_user_loc_id" id="org_user_loc_id" required>
                                @if(!$addUser)<option>Select Location</option>@endif
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}"
                                        @if(!$addUser && ($organizationUser->location_id == $location->id))
                                            selected
                                        @endif
                                    >{{ $location->name }}</option>
                                @endforeach
                            </select>
                            @error('org_user_loc_id')
                                <span class="text-danger">Select a Location</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="user_pass" class="form-label fw-semibold">5.Password</label>
                            <div class="input-group align-items-center" id="user_pass">
                                <input type="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="button-addon1"
                                    id="org_user_pass" name="org_user_pass" @if($addUser)required @endif>
                                <button class="btn btn-secondary" type="button" id="button-addon1"><i class="fi fi-rr-key me-1"></i>Generate Password</button>
                            </div>
                            <div class="form-text d-none" id="passwordHelpBlock">
                                <small class="text-muted">Password will be automatically generated with strong security requirements.</small>
                            </div>
                            @error('org_user_pass')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn w-100 mt-4
                        @if ($addUser)
                            btn-primary
                        @else
                            btn-success text-white
                        @endif
                    ">
                        @if ($addUser)
                            Create User
                        @else
                            Update User
                        @endif
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('dashboard-scripts')
    <script>
        document.getElementById('button-addon1').addEventListener('click', function() {
            const btn = this;
            const originalText = btn.innerHTML;

            btn.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Generating...';
            btn.disabled = true;

            fetch('/dashboard/generate-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('org_user_pass').value = data.password;
                    document.getElementById('passwordHelpBlock').classList.remove('d-none');

                    // Show password temporarily
                    const passwordField = document.getElementById('org_user_pass');
                    passwordField.type = 'text';
                    setTimeout(() => {
                        passwordField.type = 'password';
                    }, 3000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            })
            .finally(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        });
    </script>
@endpush