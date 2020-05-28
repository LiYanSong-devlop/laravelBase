<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

class ManageLog extends Model
{
    //
    protected $fillable = [
        'user_id', 'user_name', 'type', 'ip', 'url', 'content',
    ];
}
