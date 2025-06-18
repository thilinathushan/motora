<?php

namespace App\Console\Commands;

use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessBlockchainVehicles extends Command
{
    protected $signature = 'vehicles:process-blockchain'; // Command name
    protected $description = 'Processes vehicles and creates their records on blockchain';

    public function handle()
    {
        // Logging the start
        Log::channel('blockchain')->info('Blockchain Cron Job Started at ' . now());

        // Step 1: Fetch vehicles based on your conditions
        $vehicles = Vehicle::where('verification_score', 4)
            ->where('is_blockchain_created', 0)
            ->whereNull('blockchain_created_at')
            ->whereNull('transaction_id')
            ->get();

        if ($vehicles->isEmpty()) {
            Log::channel('blockchain')->info('No vehicles found for blockchain processing.');
            return;
        }

        $basicToken = env('BLOCKCHAIN_AUTH_KEY', 'ZTFqYWJmZmthNjpvdy1XN2tkejF6MEhXWU02VUJxaTc0SnlNLXphWW05VEREam9XQkJFcjNB');
        foreach ($vehicles as $vehicle) {
            try {
                $vehicleData = [
                    'registration_number' => $vehicle->registration_number,
                    'chassis_number' => $vehicle->chassis_number,
                    'conditions_special_notes' => $vehicle->conditions_special_notes,
                    'engine_no' => $vehicle->engine_no,
                    'cylinder_capacity' => $vehicle->cylinder_capacity,
                    'class_of_vehicle' => $vehicle->class_of_vehicle,
                    'taxation_class' => $vehicle->taxation_class,
                    'status_when_registered' => $vehicle->status_when_registered,
                    'fuel_type' => $vehicle->fuel_type,
                    'make' => $vehicle->make,
                    'country_of_origin' => $vehicle->country_of_origin,
                    'model' => $vehicle->model,
                    'manufactures_description' => $vehicle->manufactures_description,
                    'wheel_base' => $vehicle->wheel_base,
                    'over_hang' => $vehicle->over_hang,
                    'type_of_body' => $vehicle->type_of_body,
                    'year_of_manufacture' => $vehicle->year_of_manufacture,
                    'colour' => $vehicle->colour,
                    'seating_capacity' => $vehicle->seating_capacity,
                    'unladen' => $vehicle->unladen,
                    'gross' => $vehicle->gross,
                    'front' => $vehicle->front,
                    'rear' => $vehicle->rear,
                    'dual' => $vehicle->dual,
                    'single' => $vehicle->single,
                    'length_width_height' => $vehicle->length_width_height,
                    'date_of_first_registration' => $vehicle->date_of_first_registration,
                    'taxes_payable' => $vehicle->taxes_payable,
                ];

                // Create final request body
                $payload = [
                    'headers' => [
                        'type' => 'SendTransaction',
                        'signer' => 'thilinathushan',
                        'channel' => 'default-channel',
                        'chaincode' => 'motora',
                    ],
                    'func' => 'createVehicle',
                    'args' => [
                        json_encode($vehicleData)
                    ],
                    'init' => false,
                ];

                // Send request with Basic Auth
                $response = Http::withHeaders([
                        'Authorization' => 'Basic ' . $basicToken,
                    ])
                    ->post('https://e1ez488b5x-e1m60k20s4-connect.eu1-azure-ws.kaleido.io/transactions?fly-sync=true', $payload);

                // Log the API response
                Log::channel('blockchain')->info("API Response for Vehicle ID {$vehicle->id}:", $response->json());

                if ($response->successful()) {
                    $transactionId = $response->json('transactionID');

                    // Step 3: Update the vehicle
                    $vehicle->update([
                        'is_blockchain_created' => 1,
                        'blockchain_created_at' => Carbon::now(),
                        'transaction_id' => $transactionId,
                    ]);

                    Log::channel('blockchain')->info("Vehicle ID {$vehicle->id} processed. Transaction ID: {$transactionId}");
                } else {
                    Log::channel('blockchain')->error("Failed API response for Vehicle ID {$vehicle->id}. Status: {$response->status()}");
                }

                $ipfsHash = $this->uploadToIPFSWithKaleido($vehicle->certificate_url);
                $vehicle->update(['certificate_ipfs_hash' => $ipfsHash]);
            } catch (\Exception $e) {
                Log::channel('blockchain')->error("Exception for Vehicle ID {$vehicle->id}: " . $e->getMessage());
            }
        }

        Log::channel('blockchain')->info('Blockchain Cron Job Finished at ' . now());
        $this->info('✅ Blockchain vehicle processing task completed successfully.');
    }

    protected function uploadToIPFSWithKaleido($filePath)
    {
        $disk = Storage::disk('private');
        $relativePath = str_replace('storage/', '', $filePath);

        if (!$disk->exists($relativePath)) {
            Log::channel('blockchain')->error("File does not exist: {$relativePath}");
            return null;
        }

        $fileContent = $disk->get($relativePath);
        $fileName = basename($relativePath);
        $basicToken = env('BLOCKCHAIN_AUTH_KEY', 'ZTFqYWJmZmthNjpvdy1XN2tkejF6MEhXWU02VUJxaTc0SnlNLXphWW05VEREam9XQkJFcjNB');

        try {
            // Send request with Basic Auth
            $response = Http::withHeaders([
                    'Authorization' => 'Basic ' . $basicToken,
                ])
                ->attach('path', $fileContent, $fileName)
                ->post('https://e1ez488b5x-e1ekvdec3b-ipfs.eu1-azure.kaleido.io/api/v0/add');

            // Log the API response
            if ($response->successful()) {
                $result = $response->json();
                $result = $result['Hash'] ?? null;
                Log::channel('blockchain')->info("IPFS Uploaded. Hash ID: {$result}");
                return $result;

            } else {
                Log::channel('blockchain')->error("Failed API response for IPFS. Status: {$response}");
            }
        } catch (\Exception $e) {
            Log::channel('blockchain')->error("IPFS Upload Exception: " . $e->getMessage());
        }
        return null;
    }

}
