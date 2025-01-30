<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LocationOrganization extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'org_id',
        'location_id',
    ];
}
