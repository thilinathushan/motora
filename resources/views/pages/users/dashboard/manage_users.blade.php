@extends('layouts.dashboard_layout')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4 text-center">
            <div class="text-center mb-4">
                <h2 class="h2 my-3 fw-bold">
                    Manage User Details
                </h2>
                <p class="text-muted">
                    Let's Manage Organization Users 👋
                </p>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle shadow-sm rounded-3 overflow-hidden">
                    <thead class="table-dark align-middle">
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-start">User Type</th>
                            <th class="text-start">Name</th>
                            <th class="text-start">Email</th>
                            <th class="text-start">Location</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($organizationUsers as $organizationUser)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-start">{{ $organizationUser->userType->name }}</td>
                                <td class="text-start">{{ $organizationUser->name }}</td>
                                <td class="text-start">{{ $organizationUser->email }}</td>
                                <td class="text-start">{{ $organizationUser->location->name }}</td>
                                <td class="text-center">
                                    <a class="btn btn-primary m-2 text-white"
                                    href="{{ route('dashboard.organizationUser.edit', $organizationUser->id) }}"
                                    >
                                        <i class="fi fi-rr-pencil"></i> Edit
                                    </a>
                                    @if(Auth::guard('organization_user')->user()->hasRole('Organization Super Admin') ||
                                        Auth::guard('organization_user')->user()->hasRole('Organization Admin')
                                        )
                                        <a class="btn text-white
                                            @if($organizationUser->deleted_at == null) btn-danger @else btn-success  @endif"
                                            href="{{ route('dashboard.organizationUser.toggleOrganizationUser', $organizationUser->id) }}"
                                            >
                                            @if($organizationUser->deleted_at == null)<i class="fi fi-rr-trash"></i> Delete @else
                                            <i class="fi fi-rr-trash-restore"></i> Restore @endif
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted py-3" colspan="8">No Users Found!</td>
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
