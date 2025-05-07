<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleInsurance extends Model
{
    protected $fillable = [
        'vehicle_id',
        'vehicle_registration_number',
        'policy_no',
        'valid_from',
        'valid_to',
        'insurance_organization_id',
        'insurance_center_id',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
