<?php

namespace App\Jobs;

use App\Models\FaultsPredictionReports;
use App\Models\VehicleEmission;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\Log;

class GenerateFaultsPredictionPdfReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $report;

    /**
     * Create a new job instance.
     */
    public function __construct(FaultsPredictionReports $report)
    {
        $this->report = $report;
    }

    /**
     * Execute the job.
     */
    public function handle(PDF $pdf): void
    {
        $this->report->update(['pdf_status' => 'processing']);

        try {
            $vehicle = $this->report->vehicle;
            $vehicleEmissions = VehicleEmission::select([
                'id',
                'vehicle_id',
                'odometer',
                'created_at',
                'overall_status'
            ])
            ->where('vehicle_id', $vehicle->id)
            ->orderBy('created_at', 'asc')
            ->get();

            $aiData = json_decode($this->report->ai_result, true);
            $reportDate = Carbon::now()->format('Y-m-d');
            $previous_owners = json_decode($vehicle->previous_owners);
            $totalRegCount = is_array($previous_owners) ? count($previous_owners) + 1 : 1;

            /// Generate the PDF
            $pdfInstance = $pdf->loadView('pages.organization.dashboard.vehicle_details.motora_report',
                compact('vehicle', 'vehicleEmissions', 'aiData', 'reportDate', 'totalRegCount'));

            // Customize PDF settings if needed
            $pdfInstance->setPaper('a4', 'portrait');
            $pdfInstance->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isJavascriptEnabled' => true,
            ]);

            $filename = 'motora-reports/report-' . $this->report->id . '-' . time() . '.pdf';
            \Illuminate\Support\Facades\Storage::disk('private')->put($filename, $pdfInstance->output());

            // Update the report record with the success status and the file path
            $this->report->update([
                'pdf_status' => 'completed',
                'pdf_path' => $filename,
            ]);
        } catch (\Throwable $th) {
            // Log::error("PDF Generation Failed for Report ID: {$this->report->id}", [
            //     'error_message' => $th->getMessage(),
            //     'file' => $th->getFile(),
            //     'line' => $th->getLine(),
            //     // 'trace' => $th->getTraceAsString() // Uncomment for full stack trace
            // ]);

            $this->report->update([
                'pdf_status' => 'failed',
                'pdf_error_message' => $th->getMessage(),
            ]);
        }
    }
}
