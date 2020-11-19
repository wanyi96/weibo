<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        factory(User::class)->times(50)->create();

        $user = User::find(1);
        $user->name = 'wanyi';
        $user->email = '408629610@qq.com';
        $user->is_admin = true;
        $user->save();
    }
}
