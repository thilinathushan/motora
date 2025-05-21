<div wire:poll.visible>
    @if ($notificationsCount > 0)
        <div class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger d-flex align-content-center justify-content-center"
            style="width: 24px; height: 24px;">
            <span class="align-self-center fw-normal p-1" style="font-size: 0.6rem;">
                {{ $notificationsCount > 99 ? '99+' : $notificationsCount }}
            </span>
        </div>
    @endif
</div>
