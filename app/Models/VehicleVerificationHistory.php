<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleVerificationHistory extends Model
{
    protected $fillable = [
        'vehicle_id',
        'vehicle_registration_number',
        'ds_organization_id',
        'ds_center_id',
        'ds_verification',
        'ds_verification_date',
        'emission_organization_id',
        'emission_center_id',
        'emission_verification',
        'emission_verification_date',
        'insurance_organization_id',
        'insurance_center_id',
        'insurance_verification',
        'insurance_verification_date',
        'service_organization_id',
        'service_center_id',
        'service_verification',
        'service_verification_date',
    ];
}
