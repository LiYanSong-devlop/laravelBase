<?php

namespace App\Http\Controllers\Base\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Base\Admin\UserStoreRequest;
use App\Http\Requests\Base\Admin\UserUpdatePassRequest;
use App\Http\Requests\Base\Admin\UserUpdateRequest;
use App\Http\Resources\Base\Admin\UserResource;
use App\Models\Base\AdminUser;
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

    /**
     * 管理员详情
     * @return mixed
     */
    public function detail()
    {
        $user = auth('api_admin')->user();
        return $this->success(UserResource::make($user));
    }

    /**
     * 创建管理员
     * @param UserStoreRequest $request
     * @return mixed
     */
    public function store(UserStoreRequest $request)
    {
        $request_data = $request->only(['user_name','nickname','avatar','password','mobile','position']);
        if (empty($request_data['nickname'])) {
            $request_data['nickname'] = $request_data['user_name'];
        }
        $this->service->create($request_data);
        return $this->success('添加管理员成功');
    }

    /**
     * 修改自己的头像以及昵称
     * @param UserUpdateRequest $request
     * @return mixed
     */
    public function update(UserUpdateRequest $request)
    {
        $request_data = $request->only('nickname', 'avatar');
        $admin_user = auth('api_admin')->user();
        $admin_user->fill($request_data)->save();
        return $this->success('更新信息成功');
    }

    /**
     * 修改密码
     * @param UserUpdatePassRequest $request
     * @return mixed
     */
    public function updatePass(UserUpdatePassRequest $request)
    {
        $password = $request->get('password');
        try {
            AdminUser::query()->where('id', auth('api_admin')->user()->id)->update([
                'password' => bcrypt($password),
            ]);
            //注销token
            //auth('api_admin')->logout();
            return $this->success('更新密码成功');
        } catch (\Exception $exception) {
            return $this->failed('更新密码失败');
        }
    }

    /**
     * 重置密码
     * @param $user_id
     * @return mixed
     */
    public function resetPass($user_id)
    {
        $admin_user = auth('api_admin')->user();
        if (!$admin_user->compareIsMain()) {
            return $this->failed('您的账号不是超级管理员（主体账号），不允许执行此操作');
        }
        $reset_user = AdminUser::query()->where('id', $user_id)->firstOrFail();
        $reset_password = ($reset_user->user_name);
        $reset_user->fill(['password' => $reset_password])->save();
        return $this->success('重置用户密码成功');
    }
}
