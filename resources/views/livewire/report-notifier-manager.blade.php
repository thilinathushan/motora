<div>
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        @foreach ($activeReportIds as $reportId)
            @livewire('report-status-notifier', ['reportId' => $reportId], key('report-'.$reportId))
        @endforeach
    </div>
</div>
