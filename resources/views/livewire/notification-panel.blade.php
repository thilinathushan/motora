<div wire:poll.visible>
    @forelse ($notifications as $notification)
        <li class="dropdown-item text-wrap">
            <div class="row justify-content-start align-items-center">
                <div class="col-1 me-2">
                    <a href="{{ route('dashboard.vehicle.markAsRead', $notification->id) }}"
                        class="text-decoration-none d-inline-block icon-hover p-2">
                        <i class="fi fi-rr-check-circle"></i>
                    </a>
                </div>
                <div class="col">
                    <a class="text-decoration-none text-dark" href="{{ route('dashboard.vehicle.verifyVehicle', $notification->id) }}">{{ $notification->data['message'] }}</a>
                </div>
            </div>
        </li>
        @if (!$loop->last)
            <li>
                <hr />
            </li>
        @endif

        @if ($loop->last)
            <li>
                <hr />
            </li>
            <li class="dropdown-item text-wrap "><a href="{{ route('dashboard.vehicle.markAllAsRead') }}"
                    class="btn btn-secondary w-100">Mark All As Read</a></li>
        @endif
    @empty
        <li class="dropdown-item text-wrap mb-0">
            <div class="row justify-content-start align-items-center">
                <div class="col-1">
                    <i class="fi fi-rr-bell-slash"></i>
                </div>
                <div class="col">
                    <a class=" text-decoration-none text-dark" href="">No Notifications
                        found!</a>
                </div>
            </div>
        </li>
    @endforelse
</div>
