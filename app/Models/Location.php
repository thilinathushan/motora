<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'district_id',
        'province_id',
        'name',
        'address',
        'postal_code',
        'phone_number'
    ];

    public function users()
    {
        return $this->hasMany(OrganizationUser::class);
    }
}
