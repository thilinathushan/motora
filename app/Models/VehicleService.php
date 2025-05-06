<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleService extends Model
{
    protected $fillable = [
        'vehicle_id',
        'vehicle_registration_number',
        'current_milage',
        'next_service_milage',
        'is_engine_oil_change',
        'is_engine_oil_filter_change',
        'is_brake_oil_change',
        'is_brake_pad_change',
        'is_transmission_oil_change',
        'is_deferential_oil_change',
        'is_headlights_okay',
        'is_signal_light_okay',
        'is_brake_lights_okay',
        'is_air_filter_change',
        'is_radiator_oil_change',
        'is_ac_filter_change',
        'ac_gas_level',
        'is_tyre_air_pressure_ok',
        'tyre_condition',
        'vehicle_service_organization_id',
        'vehicle_service_center_id'
    ];
}
