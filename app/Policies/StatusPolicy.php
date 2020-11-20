<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Status;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatusPolicy
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

    //删除时判断，当前用户和微博作者是否是同一id
    public function destroy(User $user,Status $status)
    {
        return $user->id === $status->user_id;
    }
}
