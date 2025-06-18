@extends('layouts.dashboard_layout')

@section('content')
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <h2 class="h2 my-3 fw-bold">
                    Vehicle Faults Prediction Report
                </h2>
                <p class="text-muted">
                    AI powered Vehicle Faults Prediction Report. <i class="fi fi-rr-sparkles"></i>
                </p>

            </div>
            <div class="rounded-4">
                <table class="table table-borderless table-striped align-middle shadow-sm rounded-3 overflow-hidden">
                    <thead>
                        <tr>
                            <th class="text-start p-3">
                                <img src="{{ asset('motora-logo-4.png') }}" alt="" width="100px">
                            </th>
                            <th></th>
                        </tr>
                        <tr>
                            <th class="text-start p-3">
                                <h4 class="text-muted fw-bold fs-3">{{ $vehicle->make }}</h4>
                            </th>
                            <th class="text-end p-3">
                                <span class="fs-3 p-3"
                                    style="font-family: Arial, Verdana, Helvetica; background-color:rgb(240, 204, 59); color: rgb(25, 25, 25);">{{ $vehicle->registration_number }}</span>
                            </th>
                        </tr>
                        <tr>
                            <th></th>
                            <th class="text-end pe-3">
                                <span class="fw-bold">Report Date:</span><span class="fw-normal">{{ $reportDate }}</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- general info --}}
                        <tr style="border-bottom: 2px solid #0074e4;">
                            <td class="ps-3 text-start fw-bold" style="background-color: #0074e4; color:white; width:25%;">
                                General Information</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table
                                    class="table table-borderless table-striped table-hover align-middle shadow-sm overflow-hidden">
                                    <tbody>
                                        <tr>
                                            <td class="text-start">
                                                <span class="fw-bold">Make</span>
                                            </td>
                                            <td>
                                                <span class="fw-normal">{{ $vehicle->make }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">
                                                <span class="fw-bold">Model</span>
                                            </td>
                                            <td>
                                                <span class="fw-normal">{{ $vehicle->model }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">
                                                <span class="fw-bold">Colour</span>
                                            </td>
                                            <td>
                                                <span class="fw-normal">{{ $vehicle->colour }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">
                                                <span class="fw-bold">Year of Manufacture</span>
                                            </td>
                                            <td>
                                                <span class="fw-normal">{{ $vehicle->year_of_manufacture }}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>

                        {{-- engine & fuel info --}}
                        <tr style="border-bottom: 2px solid #0074e4;">
                            <td class="ps-3 text-start fw-bold" style="background-color: #0074e4; color:white; width:25%;">
                                Engine & Fuel Information</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table
                                    class="table table-borderless table-striped table-hover align-middle shadow-sm overflow-hidden">
                                    <tbody>
                                        <tr>
                                            <td class="text-start">
                                                <span class="fw-bold">Engine Capacity</span>
                                            </td>
                                            <td>
                                                <span class="fw-normal">{{ $vehicle->cylinder_capacity }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">
                                                <span class="fw-bold">Fuel Type</span>
                                            </td>
                                            <td>
                                                <span class="fw-normal">{{ $vehicle->fuel_type }}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>

                        {{-- owner info --}}
                        <tr style="border-bottom: 2px solid #0074e4;">
                            <td class="ps-3 text-start fw-bold" style="background-color: #0074e4; color:white; width:25%;">
                                Ownership Information</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table
                                    class="table table-borderless table-striped table-hover align-middle shadow-sm overflow-hidden">
                                    <tbody>
                                        <tr>
                                            <td class="text-start">
                                                <span class="fw-bold">Total Registrations</span>
                                            </td>
                                            <td>
                                                <span class="fw-normal">{{ $totalRegCount }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">
                                                <span class="fw-bold">First Registrations</span>
                                            </td>
                                            <td>
                                                <span class="fw-normal">{{ $vehicle->date_of_first_registration }}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>

                        {{-- Mileage Info --}}
                        <tr style="border-bottom: 2px solid #0074e4;">
                            <td class="ps-3 text-start fw-bold" style="background-color: #0074e4; color:white; width:25%;">
                                Mileage Information</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table
                                    class="table table-borderless table-striped table-hover align-middle shadow-sm overflow-hidden">
                                    <tbody>
                                        <tr>
                                            <td class="text-start">
                                                <span class="fw-bold">Odometer</span>
                                            </td>
                                            <td>
                                                <span class="fw-normal">in Kilo Meters</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">
                                                <span class="fw-bold">Emission Certificates</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="fw-normal">{{ !is_null($vehicleEmissions) ? count($vehicleEmissions) : 0 }}</span>
                                            </td>
                                        </tr>
                                        @foreach ($vehicleEmissions as $vehicleEmission)
                                            @if ($loop->first)
                                                <tr>
                                                    <td class="text-start">
                                                        <span class="fw-bold">First Registration</span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="fw-normal">{{ $vehicleEmission->created_at->format('Y-m-d') }}</span>
                                                    </td>
                                                </tr>
                                            @endif

                                            @if ($loop->last)
                                                <tr>
                                                    <td class="text-start">
                                                        <span class="fw-bold">Last Registrations</span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="fw-normal">{{ $vehicleEmission->created_at->format('Y-m-d') }}</span>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        <tr>
                                            <td colspan="2">
                                                <canvas id="mileageChart" width="300" height="100"></canvas>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>

                        {{-- Dimensions Info --}}
                        <tr style="border-bottom: 2px solid #0074e4;">
                            <td class="ps-3 text-start fw-bold" style="background-color: #0074e4; color:white; width:25%;">
                                Dimensions & Weight Information</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table
                                    class="table table-borderless table-striped table-hover align-middle shadow-sm overflow-hidden">
                                    <tbody>
                                        @php
                                            $dimensions = explode(' ', $vehicle->length_width_height);
                                        @endphp
                                        <tr>
                                            <td class="text-start">
                                                <span class="fw-bold">Width</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="fw-normal">{{ isset($dimensions[0]) ? $dimensions[0] : 'N/A' }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">
                                                <span class="fw-bold">Height</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="fw-normal">{{ isset($dimensions[1]) ? $dimensions[1] : 'N/A' }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">
                                                <span class="fw-bold">Length</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="fw-normal">{{ isset($dimensions[2]) ? $dimensions[2] : 'N/A' }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">
                                                <span class="fw-bold">Wheel Base</span>
                                            </td>
                                            <td>
                                                <span class="fw-normal">{{ $vehicle->wheel_base }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">
                                                <span class="fw-bold">Weight</span>
                                            </td>
                                            <td>
                                                <span class="fw-normal">{{ $vehicle->unladen }}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>

                        {{-- Other Info --}}
                        <tr style="border-bottom: 2px solid #0074e4;">
                            <td class="ps-3 text-start fw-bold" style="background-color: #0074e4; color:white; width:25%;">
                                Other Information</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table
                                    class="table table-borderless table-striped table-hover align-middle shadow-sm overflow-hidden">
                                    <tbody>
                                        <tr>
                                            <td class="text-start">
                                                <span class="fw-bold">Seating Capacity</span>
                                            </td>
                                            <td>
                                                <span class="fw-normal">{{ $vehicle->seating_capacity }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">
                                                <span class="fw-bold">Provincial Council</span>
                                            </td>
                                            <td>
                                                <span class="fw-normal">{{ $vehicle->provincial_council }}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>

                        {{-- Prediction Report --}}
                        <tr style="border-bottom: 2px solid #0074e4;">
                            <td class="ps-3 text-start fw-bold"
                                style="background-color: #0074e4; color:white; width:25%;">Faults Prediction Information
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table
                                    class="table table-borderless table-striped table-hover align-middle shadow-sm overflow-hidden">
                                    <tbody>
                                        <tr>
                                            <td colspan="2" class="text-start">
                                                <span class="fw-normal">{{ $aiData['explanation'] }}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="my-4 w-100 text-center">
                @livewire('pdf-download-button', ['report' => $report])
                {{-- <form action="{{ route('dashboard.vehicle.downloadMotoraReport') }}" method="post">
                    @csrf
                    <input type="hidden" name="vehicle_details" value="{{ $vehicle }}">
                    <input type="hidden" name="reportDate" value="{{ $reportDate }}">
                    <input type="hidden" name="totalRegCount" value="{{ $totalRegCount }}">
                    <input type="hidden" name="vehicleEmissions" value="{{ $vehicleEmissions }}">
                    <input type="hidden" name="aiData" value="{{ json_encode($aiData) }}">

                    <button type="submit" class="btn btn-primary">Download Report</button>
                </form> --}}
            </div>
            <p class="mt-5 text-muted text-center">
                The report may include incorrect predictions. Please use it as a reference and consult with a qualified
                mechanic for accurate diagnosis and repairs. Thank you for your understanding.
            </p>
        </div>
    </div>
@endsection

@push('dashboard-scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the vehicle emissions data from PHP
            const vehicleEmissions = @json($vehicleEmissions);

            // Process data for the chart
            const chartData = processEmissionsData(vehicleEmissions);

            // Create the chart
            const ctx = document.getElementById('mileageChart').getContext('2d');
            const mileageChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.years,
                    datasets: [{
                        label: 'Vehicle Mileage Over Time',
                        data: chartData.mileages,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: false,
                            title: {
                                display: true,
                                text: 'Mileage (km)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Year'
                            }
                        }
                    }
                }
            });
        });

        // Function to process emissions data for the chart
        function processEmissionsData(emissions) {
            // Initialize arrays for chart data
            const years = [];
            const mileages = [];

            // Sort emissions by date
            emissions.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));

            // Extract year and mileage from each emission record
            emissions.forEach(emission => {
                const date = new Date(emission.created_at);
                const year = date.getFullYear();

                // Convert odometer to number (assuming it's stored as string)
                const mileage = parseInt(emission.odometer, 10);

                years.push(year);
                mileages.push(mileage);
            });

            return {
                years: years,
                mileages: mileages
            };
        }
    </script>
@endpush
