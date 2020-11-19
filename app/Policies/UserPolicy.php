<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //接收两个参数，第一个参数默认为当前登录用户实例，第二个参数为要授权的用户实例。
    //当两个id相同时，则代表两个用户是相同用户，用户通过授权，否则，会抛出403异常
    public function update(User $currentUser,User $user)
    {
        return $currentUser->id === $user->id;
    }
}
