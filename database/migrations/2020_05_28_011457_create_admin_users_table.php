<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_users', function (Blueprint $table) {
            $table->id();
            $table->string('user_name')->comment('用户名');
            $table->string('password')->comment('密码');
            $table->string('nickname')->comment('昵称');
            $table->string('position')->nullable()->comment('职位');
            $table->string('mobile')->nullable()->comment('电话');
            $table->string('avatar')->nullable()->comment('头像');
            $table->tinyInteger('status')->default(0)->comment('状态：0正常 1密码错误被锁定  2 超级管理员永久锁定');
            $table->tinyInteger('is_main')->default(0)->comment('账号：0管理员 1 超级管理员');
            $table->dateTime('lock_time')->nullable()->comment('锁定时间');
            $table->dateTime('update_password_time')->nullable()->comment('修改密码时间');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_users');
    }
}
