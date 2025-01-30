<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class OrganizationUser extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, SoftDeletes;

    protected $fillable = [
        'u_tp_id',
        'org_cat_id',
        'loc_org_id',
        'name',
        'email',
        'password',
        'phone_number',
    ];

    // implement the relation between OrganizationUser and UserType
    public function userType()
    {
        return $this->belongsTo(UserType::class, 'u_tp_id');
    }
}
