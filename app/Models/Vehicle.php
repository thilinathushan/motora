<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'registration_number',
        'chassis_number',
        'current_owner_address_idNo',
        'conditions_special_notes',
        'absolute_owner',
        'engine_no',
        'cylinder_capacity',
        'class_of_vehicle',
        'taxation_class',
        'status_when_registered',
        'fuel_type',
        'make',
        'country_of_origin',
        'model',
        'manufactures_description',
        'wheel_base',
        'over_hang',
        'type_of_body',
        'year_of_manufacture',
        'colour',
        'previous_owners',
        'seating_capacity',
        'unladen',
        'gross',
        'front',
        'rear',
        'dual',
        'single',
        'length_width_height',
        'provincial_council',
        'date_of_first_registration',
        'taxes_payable',
        'verification_score',
        'certificate_url',
        'is_blockchain_created',
        'blockchain_created_at',
        'transaction_id'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_vehicles');
    }

    public function vehicleRevenueLicenses()
    {
        return $this->hasMany(VehicleRevenueLicense::class);
    }
}
