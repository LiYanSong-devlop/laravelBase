<?php

namespace App\Http\Controllers\Base\Admin\RolePermission;

use App\Http\Controllers\Controller;
use App\Http\Requests\Base\RolePermission\RoleStoreRequest;
use App\Http\Requests\Base\RolePermission\RoleUpdateRequest;
use App\Http\Requests\Base\RolePermission\SetAdminUserRoleRequest;
use App\Http\Resources\Base\Admin\PermissionResource;
use App\Http\Resources\Base\Admin\RoleResource;
use App\Http\Resources\Base\Admin\RoleResourceCollection;
use App\Models\Base\AdminUser;
use App\Models\Base\RolePermission\Role;
use App\Service\RolePermission\RoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    //角色控制器
    protected $service;
    public function __construct(RoleService $service)
    {
        $this->service = $service;
    }

    /**
     * 角色列表
     * 分页
     * 模糊查询：名称 name
     * @return mixed
     */
    public function index()
    {
        $list = $this->service->getQuery();
        if (\request()->has('page')) {
            return $this->success(RoleResourceCollection::make($list));
        }else{
            return $this->success(RoleResource::collection($list));
        }
    }

    /**
     * 角色创建
     * @param RoleStoreRequest $request
     * @return mixed
     */
    public function store(RoleStoreRequest $request)
    {
        $request_data = $request->only('name','guard_name');
        $order_data = $request->get('filed');
        $this->service->add($request_data,$order_data);
        return $this->success('创建成功');
    }

    /**
     * 角色详情
     * @param Role $role
     * @return mixed
     */
    public function show(Role $role)
    {
        $role->permissions;
        return $this->success(RoleResource::make($role));
    }

    /**
     * 获取某个角色下的所有权限
     * @param Role $role
     * @return mixed
     */
    public function getPermissionByRole(Role $role)
    {
        $permission = $role->getAllPermissions();
        return $this->success(PermissionResource::collection($permission));
    }

    /**
     * 更新用户，
     * 选择设置用户权限（可设置，可不设置）
     * @param RoleUpdateRequest $request
     * @param Role $role
     * @return mixed
     * @throws \Throwable
     */
    public function update(RoleUpdateRequest $request,Role $role)
    {
        $basics_data = $request->only('name', 'guard_name');
        $other_data = $request->get('permissions');
        $result = $this->service->updateDataAndPermission($role,$basics_data,$other_data);
        if ($result) {
            return $this->success('更新角色成功');
        }else{
            return $this->failed('更新角色失败');
        }
    }

    /**
     * 删除角色
     * 同时删除角色与权限的关系
     * @param Role $role
     * @return mixed
     */
    public function destroy(Role $role)
    {
        \DB::beginTransaction();
        try {
            $role->permissions()->detach();
            $role->delete();
            \DB::commit();
            return $this->success('删除成功');
        } catch (\Exception $exception) {
            \DB::rollBack();
            return $this->failed('删除失败');
        }
    }

    /**
     * 赋予当前管理员执行某个角色
     * @param SetAdminUserRoleRequest $request
     * @return mixed
     */
    public function setAdministratorRole(SetAdminUserRoleRequest $request)
    {
        //判断超级管理员ID是否存在于请求数据中
        $user = auth('api_admin')->user();
        if (!$user->compareIsMain()) {
            return $this->failed('当前管理员，不是超级管理员，请核对后进行操作');
        }
        $other_user_id = $request->get('user_id');
        $user_info = AdminUser::query()->where('id', $other_user_id)->first();
        $basics_data = $request->get('role_ids');
        try {
            $user_info->assignRole($basics_data);
            \DB::commit();
            return $this->success('赋予管理员角色成功');
        } catch (\Exception $exception) {
            \DB::rollBack();
            return $this->failed('赋予管理员角色失败');
        }
    }
}
