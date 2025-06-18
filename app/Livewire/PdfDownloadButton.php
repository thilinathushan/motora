<?php

namespace App\Livewire;

use App\Http\Controllers\Dashboard\DashboardController;
use App\Models\FaultsPredictionReports;
use Livewire\Component;

class PdfDownloadButton extends Component
{
    public FaultsPredictionReports $report;
    public string $pdfStatus;

    public function mount(FaultsPredictionReports $report)
    {
        $this->report = $report;
        $this->pdfStatus = $report->pdf_status ?? 'pending';
    }

    public function startGeneration()
    {
        // Call the controller method to start the job
        app(DashboardController::class)->startPdfGeneration($this->report);
        $this->pdfStatus = 'processing';
    }

    public function checkStatus()
    {
        $this->report->refresh();
        $this->pdfStatus = $this->report->pdf_status;
        if ($this->pdfStatus === 'completed' || $this->pdfStatus === 'failed') {
            $this->dispatch('stop-pdf-polling');
        }
    }

    public function render()
    {
        return view('livewire.pdf-download-button');
    }
}