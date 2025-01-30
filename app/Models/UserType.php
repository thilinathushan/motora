<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public function organizationUsers()
    {
        return $this->hasMany(OrganizationUser::class, 'u_tp_id');
    }
}
