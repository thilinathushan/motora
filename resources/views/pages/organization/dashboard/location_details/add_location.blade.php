@extends('layouts.dashboard_layout')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4 text-center">
            <div class="text-center mb-4">
                <h2 class="h2 my-3 fw-bold">
                    @if ($location_details_add)
                        Add Location Details
                    @else
                        Edit Location Details
                    @endif

                </h2>
                <p class="text-muted">
                    @if ($location_details_add)
                        Let's Add Organization Location 👋
                    @else
                        Let's Edit Organization Location 👋
                    @endif
                </p>
            </div>
            <div class="text-start">
                <form action="
                    @if ($location_details_add)
                        {{ route('dashboard.location.store') }}
                    @else
                        @isset($loc_id)
                            {{ route('dashboard.location.update', $loc_id) }}
                        @endisset
                    @endif" method="post" class="row">
                    @csrf

                    {{-- Address --}}
                    <div class="mb-3">
                        <label for="loc_address" class="form-label fw-semibold">Location Address</label>
                        <input type="text" class="form-control" id="loc_address" name="loc_address"
                            placeholder="Enter Location Address" value="@if(!$location_details_add){{ $location_details->address }}@endif"  required>
                    </div>
                    {{-- Location --}}
                    <div class="mb-3">
                        <label for="loc_location" class="form-label fw-semibold">Location</label>
                        <input type="text" class="form-control" id="loc_location" name="loc_location"
                            placeholder="Enter Location" value="@if(!$location_details_add){{ $location_details->location }}@endif" required>
                    </div>
                    {{-- Postal Code --}}
                    <div class="mb-3 col-md-6">
                        <label for="loc_postal_code" class="form-label fw-semibold">Location Postal Code</label>
                        <input type="text" class="form-control" id="loc_postal_code" name="loc_postal_code"
                            placeholder="Enter Postal Code" value="@if(!$location_details_add){{ $location_details->postal_code }}@endif" required>
                    </div>
                    {{-- Phone No --}}
                    <div class="mb-3 col-md-6">
                        <label for="loc_phone_no" class="form-label fw-semibold">Location Phone Number</label>
                        <input type="text" class="form-control" id="loc_phone_no" name="loc_phone_no"
                            placeholder="011 222 3344" maxlength="10" minlength="10" required pattern="\d{10}"
                            inputmode="numeric" onkeypress="return event.charCode >= 48 && event.charCode <= 57" value="@if(!$location_details_add){{ $location_details->phone_number }}@endif">
                    </div>
                    {{-- District --}}
                    <div class="mb-3 col-md-6">
                        <label for="loc_district" class="form-label fw-semibold">Location District</label>
                        <select class="form-select" id="loc_district" name="loc_district" required>
                            @if($location_details_add)
                                <option value="0" selected>Select Location District</option>
                            @endif
                            @foreach ($districts as $district)
                                <option value="{{ $district->id }}" @if(!$location_details_add && $location_details->district_id == $district->id) selected @endif>{{ $district->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Province --}}
                    <div class="mb-3 col-md-6">
                        <label for="loc_province" class="form-label fw-semibold">Location Province</label>
                        <select class="form-select" id="loc_province" name="loc_province" required>
                            @if($location_details_add)
                                <option value="0" selected>Select Location Province</option>
                            @endif

                            @foreach ($provinces as $province)
                                <option value="{{ $province->id }}" @if(!$location_details_add && $location_details->province_id == $province->id) selected @endif>{{ $province->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="my-5">
                        <button type="submit" id="submitButton" class="btn btn-lg w-100
                            @if($location_details_add)
                                btn-primary
                            @else
                                btn-success text-white
                            @endif">
                            @if($location_details_add)
                                Save
                            @else
                                Update
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
            $('#loc_district').on('change', function() {
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
                                $('#loc_province').val(data.province
                                    .id);
                            } else {
                                $('#loc_province').val(0);
                            }
                        },
                        error: function() {
                            alert('An error occurred while fetching the province.');
                        }
                    });
                } else {
                    $('#loc_province').val(0);
                }
            });
        });
    </script>
@endpush
