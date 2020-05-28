<?php

namespace App\Http\Controllers\Base\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Base\Admin\UserStoreRequest;
use App\Http\Resources\Base\Admin\UserResource;
use App\Service\AdminUser\AdminUserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //后台用户控制器

    protected $service;

    public function __construct(AdminUserService $service)
    {
        $this->service = $service;
    }

    public function detail()
    {
        $user = auth('api_admin')->user();
        return $this->success(UserResource::make($user));
    }

    public function store(UserStoreRequest $request)
    {
        $request_data = $request->only(['user_name','nickname','avatar','password','mobile','position']);
        if (empty($request_data['nickname'])) {
            $request_data['nickname'] = $request_data['user_name'];
        }
        //密码加密
        $request_data['password'] = bcrypt($request_data['password']);
        $this->service->create($request_data);
        return $this->success('添加管理员成功');
    }
}
