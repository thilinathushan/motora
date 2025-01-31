@extends('layouts.dashboard_layout')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4 text-center">
            <div class="text-center mb-4">
                <h2 class="h2 my-3 fw-bold">
                    @if ($organization_details_add)
                        Add Organization Details
                    @else
                        Edit Organization Details
                    @endif
                </h2>
                <p class="text-muted">
                    @if ($organization_details_add)
                        Let's complete organization profile! 👋
                    @else
                        Update Organization Details ✍️
                    @endif
                </p>
            </div>

            <div class="text-start">
                <form action="@if ($organization_details_add)
                        {{ route('dashboard.organization.store') }}
                    @else
                        {{ route('dashboard.organization.update', $organization_user_id) }}
                    @endif" method="post" class="row" enctype="multipart/form-data">

                    @csrf

                    <div class="mb-3">
                        <label for="org_name" class="form-label fw-semibold">Organization Name</label>
                        <input type="text" class="form-control" id="org_name" name="org_name"
                            placeholder="Enter Organization Name" required
                            value="@if(!$organization_details_add){{ $organization_details->name }}@endif">
                    </div>
                    <div class="mb-3">
                        <label for="org_category" class="form-label fw-semibold">Organization Category</label>
                        <input type="text" class="form-control" id="org_category" name="org_category"
                            value="{{ $org_category }}" readonly>
                    </div>

                    <div class="mb-5 col-md-6">
                        <label for="org_phone_no" class="form-label fw-semibold">Organization Phone Number</label>
                        <input type="text" class="form-control" id="org_phone_no" name="org_phone_no"
                            placeholder="011 222 3344" maxlength="10" minlength="10" required pattern="\d{10}" inputmode="numeric"
                            value="@if(!$organization_details_add){{ $location_details->phone_number }}@endif"
                            onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                    </div>
                    <div class="mb-5 col-md-6">
                        <label for="org_br" class="form-label fw-semibold">Organization Business Registration</label>
                        <input type="file" class="form-control" id="org_br" name="org_br"
                            accept=".pdf, .jpeg, .jpg, .png">
                    </div>

                    <div class="mb-5">
                        @if (!$organization_details_add && !empty($br_file_url))
                            <div class="border rounded p-3 bg-light text-center">
                                @php
                                    $fileExtension = pathinfo($organization_details->br_path, PATHINFO_EXTENSION);
                                @endphp
                                @if (in_array($fileExtension, ['jpeg', 'jpg', 'png']))
                                    <img src="{{ $br_file_url }}" alt="Business Registration"
                                        class="img-fluid rounded shadow-sm" style="max-width: 100%; height: auto;">
                                @elseif($fileExtension === 'pdf')
                                    <iframe src="{{ $br_file_url }}" width="100%" height="400px"
                                        class="rounded shadow-sm"></iframe>
                                @endif
                                <div class="my-4">
                                    <a href="{{ $br_file_url }}" class="btn btn-primary btn-sm" target="_blank">
                                        📥 View Business Registration
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="org_address" class="form-label fw-semibold">Organization Address</label>
                        <input type="text" class="form-control" id="org_address" name="org_address"
                            placeholder="Enter Main Address" required
                            value="@if(!$organization_details_add){{ $location_details->address }}@endif">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="org_location" class="form-label fw-semibold">Organization Location</label>
                        <input type="text" class="form-control" id="org_location" name="org_location"
                            placeholder="Enter Location" required
                            value="@if(!$organization_details_add){{ $location_details->location }}@endif">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="org_postal_code" class="form-label fw-semibold">Organization Postal Code</label>
                        <input type="text" class="form-control" id="org_postal_code" name="org_postal_code"
                            placeholder="Enter Postal Code" required
                            value="@if(!$organization_details_add){{ $location_details->postal_code }}@endif">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="org_district" class="form-label fw-semibold">Organization District</label>
                        <select class="form-select" id="org_district" name="org_district" required>
                            @if ($organization_details_add)
                                <option value="0" selected>Select Organization District</option>
                            @endif

                            @foreach ($districts as $district)
                                <option value="{{ $district->id }}" @if (!$organization_details_add && $location_details->district_id == $district->id) selected @endif>
                                    {{ $district->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="org_province" class="form-label fw-semibold">Organization Province</label>
                        <select class="form-select" id="org_province" name="org_province" required>
                            @if ($organization_details_add)
                                <option value="0" selected>Select Organization Province</option>
                            @endif

                            @foreach ($provinces as $province)
                                <option value="{{ $province->id }}" @if (!$organization_details_add && $location_details->province_id == $province->id) selected @endif>
                                    {{ $province->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="my-5">
                        <button type="submit" id="submitButton"
                            class="btn btn-lg w-100
                            @if (!$organization_details_add) btn-success text-white
                            @else
                                btn-primary @endif">
                            @if (!$organization_details_add)
                                Update
                            @else
                                Save
                            @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('dashboard-scripts')
    <script>
        $(document).ready(function() {
            $('#org_district').on('change', function() {
                var districtId = $(this).val();

                if (districtId != 0) {
                    // Make AJAX request to get province
                    var url = '/get-province/' + districtId;
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            if (data.province) {
                                $('#org_province').val(data.province
                                    .id);
                            } else {
                                $('#org_province').val(0);
                            }
                        },
                        error: function() {
                            alert('An error occurred while fetching the province.');
                        }
                    });
                } else {
                    $('#org_province').val(0);
                }
            });
        });
    </script>
@endpush
