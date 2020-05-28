<?php

use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\AdminUser::query()->create([
            'user_name' => 'liysong',
            'password' => bcrypt('liysong'),
            'nickname' => 'maxBoss',
            'position' => '',
            'avatar' => 'http://image.yy.com/yywebalbumbs2bucket/144152f8680f421599233c6ffcfcef49_1476265267104.jpeg',
            'status' => 0,
        ]);
    }
}
