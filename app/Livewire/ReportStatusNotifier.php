<?php

namespace App\Livewire;

use App\Models\FaultsPredictionReports;
use Livewire\Component;

class ReportStatusNotifier extends Component
{
    public FaultsPredictionReports $report;
    public $reportId;

    public function mount($reportId)
    {
        $this->reportId = $reportId;
        $this->loadReport();
    }

    public function loadReport()
    {
        $this->report = FaultsPredictionReports::find($this->reportId);
    }

    public function getStatus()
    {
        // This re-fetches the latest status from the database
        $this->report->refresh();

        // If the report is completed, we can emit an event to stop polling
        if ($this->report->status === 'completed' || $this->report->status === 'failed') {
            $this->dispatch('stop-polling');
        }
    }

    public function render()
    {
        return view('livewire.report-status-notifier');
    }
}
