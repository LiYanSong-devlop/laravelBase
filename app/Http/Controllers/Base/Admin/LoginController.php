<?php

namespace App\Http\Controllers\Base\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Base\AdminLoginRequest;
use App\Models\Base\AdminUser;
use App\Service\ManageLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{
    //后台登录控制器
    public function __construct()
    {
        auth()->shouldUse('api_admin');
    }

    /**
     * 后台管理员登录
     * 密码错误三次后，不允许登录
     * @param AdminLoginRequest $request
     * @return mixed
     */
    public function login(AdminLoginRequest $request)
    {
        $user_name = $request->get('username');
        $password = $request->get('password');
        //通过用户名查询是否存在该用户
        $user_info = AdminUser::query()->where('user_name', $user_name)->first();
        if (!$user_info) {
            return $this->failed('登录失败，用户名/密码错误', 401);
        }
        //判断用户是否被锁定
        if ($user_info->status == 2) {
            return $this->failed('用户已经被锁定，请联系管理员', 401);
        }
        //判断是否锁定
        if ($user_info->status == 1 && (time() - strtotime($user_info['lock_time'])) < 60 * 15) {
            return $this->failed('密码尝试次数过多，账号已被锁定15分钟。', 401);
        }
        //获取缓存中的数据
        $cache_key = 'userInfo-' . $user_name;
        if (!$token = auth('api_admin')->attempt(['user_name' =>$user_name, 'password' => $password])) {
            //获取token失败 密码错误
            $login_info = Cache::pull($cache_key);
            if (!$login_info) {
                $data = [
                    'num' => 1, // 尝试次数
                    'start_time' => time(),
                ];
                Cache::put($cache_key, $data, 120);
            } else {
                //10分钟内超过3次密码错误，账号被锁定15分钟
                if ($login_info['num'] == 2 && time() - $login_info['start_time'] <= 600) {
                    //锁定该用户
                    $user_info->fill(['status' => 1, 'lock_time' => date('Y-m-d H:i:s')])->save();
                    return $this->failed('登录失败，密码尝试过多，用户被锁定15分钟', 401);
                } else if ($login_info['num'] < 3 && (time() - $login_info['start_time']) < 600) {
                    //10分钟输入错误，次数num+1
                    $login_info['num'] = $login_info['num'] + 1;
                    Cache::put($cache_key, $login_info, 120);
                } else {
                    $data = [
                        'num' => 1,
                        'start_time' => time()
                    ];
                    Cache::put($cache_key, $data, 120);
                }
            }
            return $this->failed('登录失败，密码错误（还可以尝试' . (3 - ($login_info['num'] ?? 1)) . '次）', 401);
        }else{
            //清空缓存
            Cache::forget($cache_key);

            if ($user_info->status == 1) {
                $user_info->fill(['status' => 0, 'lock_time' => null])->save();
            }
            //记录日志
            ManageLogService::create('管理员登录', '用户' . $user_info->user_name . '登录成功');

            return $this->setStatusCode(201)->success([
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api_admin')->factory()->getTTL() * 60
            ]);
        }
    }
}
