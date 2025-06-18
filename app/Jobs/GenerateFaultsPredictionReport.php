<?php

namespace App\Jobs;

use App\Models\FaultsPredictionReports;
use GuzzleHttp\Client;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class GenerateFaultsPredictionReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $report;
    public $record;
    public $selectedModelVersion;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 180;

    /**
     * Create a new job instance.
     */
    public function __construct(FaultsPredictionReports $report, array $record, string $selectedModelVersion)
    {
        $this->report = $report;
        $this->record = $record;
        $this->selectedModelVersion = $selectedModelVersion;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 1. Mark the report as 'processing'
        $this->report->update(['status' => 'processing']);

        // get the ML Result
        $mlResult = $this->getResultFromMLModel($this->record);
        $aiData = [];

        if(is_array($mlResult)){
            $this->report->update(['ml_result' => json_encode($mlResult)]);
            try {
                $aiResult = $this->getPredictionFromAI($mlResult);

                if (!is_array($aiResult)) {
                    $aiData['explanation'] = 'No Predictions Available';
                    $this->report->update([
                        'status' => 'completed',
                        'ai_result' => json_encode($aiData),
                        'error_message' => $aiData['explanation']
                    ]);
                }else{
                    $aiData = $aiResult;
                    $this->report->update(['ai_result' => json_encode($aiData)]);
                    $this->report->update(['status' => 'completed']);
                }
            } catch (\Exception $e) {
                $aiData['explanation'] = 'Error connecting to prediction service.';
                $this->report->update([
                    'status' => 'completed',
                    'ai_result' => json_encode($aiData),
                    'error_message' => $aiData['explanation']
                ]);
            }
        } else {
            $mlData = $mlResult->getData(true);
            if (isset($mlData['error'])) {
                // ML response contains an error
                $aiData['explanation'] = 'No Predictions Available - Prediction Model Issue.';
                $this->report->update([
                    'status' => 'completed',
                    'ai_result' => json_encode($aiData),
                    'error_message' => $aiData['explanation']
                ]);
            }
        }
    }

    public function getResultFromMLModel($record)
    {
        // Get the current model version
        $model = Config::get('prediction_model.models')[$this->selectedModelVersion];

        // Define tthe ML Model Endpoint
        $mlEndPoint = $model['mlModel'];

        // Initialize Guzzle HTTP Client
        $client = new Client();

        try {
            // Make the POST request to the ML model endpoint
            $response = $client->post($mlEndPoint, [
                'json' => $record,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);

            // Get the response body
            $responseBody = $response->getBody()->getContents();

            // Decode the JSON response
            $responseData = json_decode($responseBody, true);

            // Return the response data
            return $responseData;
        } catch (\Throwable $th) {
            // Handle any exceptions or errors
            return response()->json([
                'error' => 'Failed to get response from ML model',
                // 'message' => $th->getMessage()
            ]);
        }
    }

    public function getPredictionFromAI($mlResult)
    {
        // Get the current model version
        $model = Config::get('prediction_model.models')[$this->selectedModelVersion];

        // Define the AI endpoint
        $aiEndpoint = $model['aiModel'];

        // Initialize Guzzle HTTP Client
        $client = new Client();

        try {
            // Make the POST request to the AI endpoint
            $response = $client->post($aiEndpoint, [
                'json' => $mlResult,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);

            // Get the response body
            $responseBody = $response->getBody()->getContents();

            // Decode the JSON response
            $responseData = json_decode($responseBody, true);

            // Return the response data
            return $responseData;
        } catch (\Throwable $th) {
            // Handle any exceptions or errors
            return response()->json([
                'error' => 'Failed to get response from ML model',
                // 'message' => $th->getMessage()
            ]);
        }
    }
}
