@extends('layouts.dashboard_layout')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <h2 class="h2 my-3 fw-bold">
                    User Profile
                </h2>
                <p class="text-muted">
                    Let's View your Profile Information 👋
                </p>
            </div>
            <div class="d-flex justify-content-center">
                <div class="p-4 rounded-4 col-8 bg-gray-100">
                    <div class="row d-flex align-items-center">
                        <div class="col-md-2 d-flex justify-content-end">
                            <h6 class="text-muted mb-0">
                                Name
                            </h6>
                        </div>
                        <div class="col-md-10">
                            <input class="form-control" type="text" name="" id="" value="{{ $user->name }}" readonly>
                        </div>
                    </div>
                    <div class="mt-4 row d-flex align-items-center">
                        <div class="col-md-2 d-flex justify-content-end">
                            <h6 class="text-muted mb-0">
                                Email
                            </h6>
                        </div>
                        <div class="col-md-10">
                            <input class="form-control" type="text" name="" id="" value="{{ $user->email }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
