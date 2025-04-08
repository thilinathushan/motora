<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleEmission extends Model
{
    protected $fillable = [
        'vehicle_id',
        'vehicle_registration_number',
        'odometer',
        'rpm_idle',
        'hc_idle',
        'co_idle',
        'rpm_2500',
        'hc_2500',
        'co_2500',
        'average_k_factor',
        'overall_status',
        'valid_from',
        'valid_to',
        'emission_test_organization_id',
        'emission_test_center_id',
    ];
}
