<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Motora Vehicle Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">

    <style>
        @page {
            margin: 0px;
        }

        .page-breaker {
            page-break-before: always;
        }

        .table-striped {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div style="padding: 24px;">
        <div class="text-center" style="margin-bottom: 24px;">
            <h2 class="h2 my-3 fw-bold">
                Vehicle Faults Prediction Report
            </h2>
            <p class="text-muted">
                AI powered Vehicle Faults Prediction Report. <img src="{{ asset('sparkles.png') }}" alt="icon"
                    width="16px">
            </p>
        </div>
        <div class="rounded-4" style="padding: 24px;">
            <table class="table table-borderless align-middle shadow-sm rounded-3 overflow-hidden">
                <thead>
                    <tr>
                        <th class="text-start" style="padding: 16px;">
                            <img src="{{ asset('motora-logo-4.png') }}" alt="" width="100px">
                        </th>
                        <th></th>
                    </tr>
                    <tr>
                        <th class="text-start" style="padding: 16px;">
                            <h4 class="text-muted fw-bold fs-3">{{ $vehicle->make }}</h4>
                        </th>
                        <th class="text-end" style="padding: 16px;">
                            <span class="fs-3"
                                style="padding: 16px; font-family: Arial, Verdana, Helvetica; background-color:rgb(240, 204, 59); color: rgb(25, 25, 25);">{{ $vehicle->registration_number }}</span>
                        </th>
                    </tr>
                    <tr>
                        <th></th>
                        <th class="text-end" style="padding-right: 16px;">
                            <span class="fw-bold">Report Date:</span><span class="fw-normal">{{ $reportDate }}</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    {{-- general info --}}
                    <tr style="border-bottom: 2px solid #0074e4;">
                        <td class="text-start fw-bold"
                            style="background-color: #0074e4; color:white; width:25%; padding-left: 16px;">
                            General Information</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <table class="table table-borderless table-hover align-middle shadow-sm overflow-hidden">
                                <tbody>
                                    <tr>
                                        <td class="text-start">
                                            <span class="fw-bold">Make</span>
                                        </td>
                                        <td>
                                            <span class="fw-normal">{{ $vehicle->make }}</span>
                                        </td>
                                    </tr>
                                    <tr class="table-striped">
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
                                    <tr class="table-striped">
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
                        <td class="text-start fw-bold"
                            style="background-color: #0074e4; color:white; width:25%; padding-left: 16px;">
                            Engine & Fuel Information</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <table class="table table-borderless table-hover align-middle shadow-sm overflow-hidden">
                                <tbody>
                                    <tr>
                                        <td class="text-start">
                                            <span class="fw-bold">Engine Capacity</span>
                                        </td>
                                        <td>
                                            <span class="fw-normal">{{ $vehicle->cylinder_capacity }}</span>
                                        </td>
                                    </tr>
                                    <tr class="table-striped">
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
                        <td class="text-start fw-bold"
                            style="padding-left: 16px; background-color: #0074e4; color:white; width:25%;">
                            Ownership Information</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <table class="table table-borderless table-hover align-middle shadow-sm overflow-hidden">
                                <tbody>
                                    <tr>
                                        <td class="text-start">
                                            <span class="fw-bold">Total Registrations</span>
                                        </td>
                                        <td>
                                            <span class="fw-normal">{{ $totalRegCount }}</span>
                                        </td>
                                    </tr>
                                    <tr class="table-striped">
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
                        <td class="text-start fw-bold"
                            style="padding-left: 16px; background-color: #0074e4; color:white; width:25%;">
                            Mileage Information</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <table class="table table-borderless table-hover align-middle shadow-sm overflow-hidden">
                                <tbody>
                                    <tr>
                                        <td class="text-start">
                                            <span class="fw-bold">Odometer</span>
                                        </td>
                                        <td>
                                            <span class="fw-normal">in Kilo Meters</span>
                                        </td>
                                    </tr>
                                    <tr class="table-striped">
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
                                                        class="fw-normal">{{ Carbon\Carbon::parse($vehicleEmission->created_at)->format('Y-m-d') }}</span>
                                                </td>
                                            </tr>
                                        @endif

                                        @if ($loop->last)
                                            <tr class="table-striped">
                                                <td class="text-start">
                                                    <span class="fw-bold">Last Registrations</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="fw-normal">{{ Carbon\Carbon::parse($vehicleEmission->created_at)->format('Y-m-d') }}</span>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach

                                    {{-- <tr>
                                            <td colspan="2">
                                                <canvas id="mileageChart" width="300" height="100"></canvas>
                                            </td>
                                        </tr> --}}
                                </tbody>
                            </table>
                        </td>
                    </tr>

                    <div class="page-breaker"></div>

                    {{-- Dimensions Info --}}
                    <tr style="border-bottom: 2px solid #0074e4;">
                        <td class="text-start fw-bold"
                            style="padding-left: 16px; background-color: #0074e4; color:white; width:25%;">
                            Dimensions & Weight Information</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <table class="table table-borderless table-hover align-middle shadow-sm overflow-hidden">
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
                                    <tr class="table-striped">
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
                                    <tr class="table-striped">
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
                        <td class="text-start fw-bold"
                            style="padding-left: 16px; background-color: #0074e4; color:white; width:25%;">
                            Other Information</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <table class="table table-borderless table-hover align-middle shadow-sm overflow-hidden">
                                <tbody>
                                    <tr>
                                        <td class="text-start">
                                            <span class="fw-bold">Seating Capacity</span>
                                        </td>
                                        <td>
                                            <span class="fw-normal">{{ $vehicle->seating_capacity }}</span>
                                        </td>
                                    </tr>
                                    <tr class="table-striped">
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
                        <td class="text-start fw-bold"
                            style="padding-left: 16px; background-color: #0074e4; color:white; width:25%;">Faults
                            Prediction
                            Information
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <table class="table table-borderless table-hover align-middle shadow-sm overflow-hidden">
                                <tbody>
                                    <tr>
                                        <td colspan="2" class="text-start">
                                            <span class="fw-normal">{{ $aiData['explanation'] ?? 'No explanation available.' }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <hr />
        <p class="text-muted text-center" style="margin-top: 48px;">
            The report may include incorrect predictions. Please use it as a reference and consult with a qualified
            mechanic for accurate diagnosis and repairs. Thank you for your understanding.
        </p>
    </div>
</body>

</html>
