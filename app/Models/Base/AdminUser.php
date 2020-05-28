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

    const MAIN = 1; //主体账号

    public function compareIsMain()
    {
        return $this->is_main == self::MAIN;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

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
