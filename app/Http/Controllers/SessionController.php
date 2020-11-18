<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionController extends Controller
{
    //
    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {
        $credentials = $this->validate($request,[
            'email'=>'required|email|max:255',
            'password'=>'required'
        ]);
        //借助Laravel提供的Auth的attempt方法可以很方便的完成用户的身份认证操作
        if(Auth::attempt($credentials)){
            //登录成功后的相关操作，提示，重定向
            session()->flash('success','欢迎登录');
            return redirect()->route('users.show',[Auth::user()]);  //Auth::user()获取当前登录用户
        }else{
            //登录失败后的操作
            session()->flash('danger','很抱歉，您的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }
        return ;
    }
}
