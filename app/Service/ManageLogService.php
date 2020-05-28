<?php


namespace App\Service;


use App\Models\Base\ManageLog;

class ManageLogService
{
    public static function create($type, $content)
    {
        if (auth('api_admin')->check()) {
            $user_id = auth('api_admin')->user()->id;
            $user_name = auth('api_admin')->user()->user_name;
        }else{
            return true;
        }
        $ip = request()->getClientIp();
        $url = request()->getRequestUri();
        return ManageLog::query()->create([
            'user_id' => $user_id,
            'user_name' => $user_name,
            'ip' => $ip,
            'url' => $url,
            'type' => $type,
            'content' => $content
        ]);
    }
}
