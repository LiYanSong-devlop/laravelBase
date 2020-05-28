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


    });
});
