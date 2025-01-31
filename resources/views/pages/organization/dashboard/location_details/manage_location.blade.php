@extends('layouts.dashboard_layout')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4 text-center">
            <div class="text-center mb-4">
                <h2 class="h2 my-3 fw-bold">
                    Manage Location Details
                </h2>
                <p class="text-muted">
                    Let's Manage Organization Locations 👋
                </p>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle shadow-sm rounded-3 overflow-hidden">
                    <thead class="table-dark align-middle">
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-start">Location</th>
                            <th class="text-start">Address</th>
                            <th class="text-start">District</th>
                            <th class="text-start">Province</th>
                            <th class="text-center">Postal Code</th>
                            <th class="text-center">Phone Number</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($locationDetails as $locationDetail)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-start">{{ $locationDetail->name }}</td>
                                <td class="text-start">{{ $locationDetail->address }}</td>
                                <td class="text-start">{{ $locationDetail->district }}</td>
                                <td class="text-start">{{ $locationDetail->province }}</td>
                                <td class="text-center">{{ $locationDetail->postal_code }}</td>
                                <td class="text-center">{{ $locationDetail->phone_number }}</td>
                                <td class="text-center">
                                    <a class="btn btn-primary m-2" href="{{ route('dashboard.editLocationDetails', $locationDetail->id) }}">
                                        <i class="fi fi-rr-pencil"></i> Edit
                                    </a>
                                    <a class="btn text-white @if($locationDetail->deleted_at == null) btn-danger @else btn-success  @endif" href="{{ route('dashboard.location.toggle', $locationDetail->id) }}">
                                        @if($locationDetail->deleted_at == null)<i class="fi fi-rr-trash"></i> Delete @else
                                        <i class="fi fi-rr-trash-restore"></i> Restore @endif
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted py-3" colspan="8">No Locations Found!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection

@push('dashboard-scripts')
@endpush
