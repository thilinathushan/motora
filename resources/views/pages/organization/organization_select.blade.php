@extends('layouts.auth_layout')

@section('auth-content')
    <div class="card border-0 shadow-lg rounded-4 bg-light">
        <div class="card-body p-4 text-center">
            {{-- logo --}}
            <div class="text-center mb-4">
                <img src="/motora-logo-4.png" alt="motora-logo" width="180">
                <h2 class="h2 my-3 fw-bold">Select an Organization</h2>
                <p class="text-muted">Let's get started with your account 👋</p>
            </div>

            <div class="text-start">
                <div class="mb-3">
                    <label for="organization_category_id" class="form-label fw-semibold">Select the Organization
                        Category</label>
                    <select class="form-select" id="organization_category_id">
                        <option value="" selected disabled>Select an Organization Category</option>
                        @foreach ($organization_categories as $organization_category)
                            <option value="{{ $organization_category->id }}">{{ $organization_category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="my-4">
                    <button type="button" id="submitButton" class="btn btn-primary btn-lg w-100">Select</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('auth-scripts')
    <script>
        $('#submitButton').on('click', function() {
            const selectedOrg = $('#organization_category_id').val();
            if (selectedOrg) {
                window.location.href = `/organization/${selectedOrg}/register`;
            }
        });
    </script>
@endpush
