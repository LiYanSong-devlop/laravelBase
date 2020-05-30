<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//基础路由
Route::namespace('Base')->group(function () {
    //基础后台路由
    Route::namespace('Admin')->group(function () {
        //后台登录
        Route::post('admin/base/login', 'LoginController@login');
        //登录后允许访问的路由 'role-permission'
        Route::middleware(['jwt:api_admin'])->group(function () {
            //当前用户详情
            Route::get('admin/base/user-info', 'UserController@detail');
            //添加管理员
            Route::post('admin/base/user-store', 'UserController@store');
            //上传图片到腾讯云上的对象存储
            Route::post('admin/base/upload', 'CommonController@upload');

            /***角色 - 权限***/
            Route::namespace('RolePermission')->group(function () {
                //赋予其他管理员执行某个角色
                Route::post('admin/base/admin-user-role', 'RoleController@setAdministratorRole');
                //获取某个角色下面的权限
                Route::get('admin/base/role-has-permission/{role}', 'RoleController@getPermissionByRole');
                //角色相关基础功能
                Route::resource('admin/base/role', 'RoleController');
                //权限相关基础功能
                Route::resource('admin/base/permission', 'PermissionController');
            });
        });

    });
});
