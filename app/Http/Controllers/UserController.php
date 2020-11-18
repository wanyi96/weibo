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

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
        ]);

        //注册后自动登录
        Auth::login($user);
        session()->flash('success','欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route('users.show',[$user]);
    }
}
