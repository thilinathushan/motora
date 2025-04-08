<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleRevenueLicense extends Model
{
    protected $fillable = [
        'vehicle_id',
        'vehicle_registration_number',
        'license_date',
        'license_no',
        'valid_from',
        'valid_to',
    ];
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }
}
