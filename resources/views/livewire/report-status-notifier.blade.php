<div>
    <!-- The main toast container -->
    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" wire:poll.5s.keep-alive="getStatus">
        <!-- This is the magic: polls every 5s -->

        <div class="toast-header">
            <strong class="me-auto">Report Generation</strong>
            <small>{{ $report->created_at->diffForHumans() }}</small>
        </div>

        <div class="toast-body">
            @if ($report->status === 'pending' || $report->status === 'processing')
                <div class="d-flex align-items-center">
                    <div class="spinner-border spinner-border-sm me-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span>Generating report for vehicle <strong>{{ $report->vehicle->registration_number }}</strong>...</span>
                </div>
            @elseif ($report->status === 'completed')
                <div class="d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fi fi-rr-check-circle me-2"></i>
                        Report for <strong>{{ $report->vehicle->registration_number }}</strong> is ready!
                    </span>
                    <a href="{{ route('dashboard.report.show', $report) }}" class="btn btn-sm btn-primary">View</a>
                </div>
            @elseif ($report->status === 'failed')
                <div class="text-danger">
                    <i class="fi fi-rr-exclamation me-2"></i>
                    Failed to generate report. <br>
                    <small>Error: {{ $report->error_message }}</small>
                </div>
            @endif
        </div>
    </div>

    <script>
        // This script listens for the 'stop-polling' event from the component
        // and removes the wire:poll attribute to save resources.
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('stop-polling', (event) => {
                let component = Livewire.find(event.id);
                if (component) {
                    component.el.removeAttribute('wire:poll.5s.keep-alive');
                }
            });
        });
    </script>
</div>
