<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminUser extends Model
{
    //
    protected $fillable = [
        'user_name', 'password', 'nickname', 'position', 'avatar', 'status', 'lock_time',
        'update_password_time'
    ];

    protected $hidden = [
        'password'
    ];
}
