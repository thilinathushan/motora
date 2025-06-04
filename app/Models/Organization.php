<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'org_cat_id',
        'name',
        'main_location_id',
        'br_path'
    ];

    public function users()
    {
        return $this->hasMany(OrganizationUser::class);
    }

    public function category()
    {
        return $this->belongsTo(OrganizationCategory::class, 'org_cat_id');
    }
}
