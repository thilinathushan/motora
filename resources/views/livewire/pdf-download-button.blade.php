<div>
    @if ($pdfStatus === 'pending')
        <!-- The Initial Button -->
        <button wire:click="startGeneration" wire:loading.attr="disabled" class="btn btn-primary d-inline-flex align-items-center">

            <!-- Spinner (hidden by default, shown on click) -->
            <span wire:loading wire:target="startGeneration" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>

            <!-- Text (shown by default, hidden on click) -->
            <span wire:loading.remove wire:target="startGeneration">Download Report</span>

            <!-- Loading Text (hidden by default, shown on click) -->
            <span wire:loading wire:target="startGeneration">Initiating...</span>

        </button>

    @elseif ($pdfStatus === 'processing')
        <!-- The Polling Button - WITH THE FIX -->
        <button class="btn btn-secondary d-inline-flex align-items-center" disabled wire:poll.3s="checkStatus">
            <!-- This is the fix: d-flex and align-items-center wrapper -->
            <div class="d-flex align-items-center">
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                <span>Preparing PDF...</span>
            </div>
        </button>

    @elseif ($pdfStatus === 'completed')
        <!-- The Success Button -->
        <a href="{{ route('dashboard.report.downloadPdf', ['report' => $report, 'filename' => 'report.pdf']) }}" class="btn btn-success text-white d-inline-flex align-items-center">
            <i class="fi fi-rr-check-circle me-2"></i>
            <span>Download Ready</span>
        </a>

    @elseif ($pdfStatus === 'failed')
        <!-- The Failure Button -->
        <button class="btn btn-danger text-white d-inline-flex align-items-center" disabled>
            <i class="fi fi-rr-exclamation me-2"></i>
            <span>Generation Failed</span>
        </button>
    @endif
</div>