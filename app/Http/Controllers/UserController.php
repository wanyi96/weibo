<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    //
    public function create()
    {
        return view('users.create');
    }

    public function show(User $user)
    {
        //将用户对象 $user 通过 compact 方法转化为一个关联数组，
        //并作为第二个参数传递给 view 方法，将数据与视图进行绑定。
        return view('users.show',compact('user'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'=>'required|unique:users|max:50',
            'email'=>'required|email|unique:users|max:225',
            'password'=>'required|confirmed|max:6',
        ]);
        return;
    }
}
