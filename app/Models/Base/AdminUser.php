<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class AdminUser extends Authenticatable implements JWTSubject
{
    //
    protected $fillable = [
        'user_name', 'password', 'nickname', 'position', 'avatar', 'status', 'lock_time',
        'update_password_time'
    ];

    protected $hidden = [
        'password'
    ];

    public function getJWTCustomClaims()
    {
        return [
            'guard' => 'api_admin'
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
}
