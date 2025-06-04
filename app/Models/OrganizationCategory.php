<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizationCategory extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['name'];

    public function organizations()
    {
        return $this->hasMany(Organization::class, 'org_cat_id');
    }

}
