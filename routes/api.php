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
        Route::post('admin/base/login', 'LoginController@login');  //后台登录
        //登录后允许访问的路由
        Route::middleware('jwt:api_admin')->group(function () {
            Route::get('admin/base/user-info', 'UserController@detail');//当前用户详情

            Route::post('admin/base/user-store', 'UserController@store');//添加管理员
        });

    });
});
