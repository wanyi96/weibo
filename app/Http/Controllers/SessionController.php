<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionController extends Controller
{
    public function __construct()
    {
        //只让未登录用户访问登录页面
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }
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
        //1.借助Laravel提供的Auth的attempt方法可以很方便的完成用户的身份认证操作
        //2.Auth::attempt() 方法可接收两个参数，第一个参数为需要进行用户身份认证的数组，
        //3.第二个参数为是否为用户开启『记住我』功能的布尔值
        if(Auth::attempt($credentials,$request->has('remember'))){
            if(Auth::user()->activated){
                //登录成功后的相关操作，提示，重定向
                session()->flash('success','欢迎登录');
                //重定向到上次访问的地址
                $fallback = route('users.show',Auth::user());
                return redirect()->intended($fallback);  //Auth::user()获取当前登录用户
            }else{
                Auth::logout();
                session()->flash('warning','你的账号未激活，请检查邮箱中的注册邮件进行激活');
                return redirect('/');
            }
        }else{
            //登录失败后的操作
            session()->flash('danger','很抱歉，您的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }
        return ;
    }

    public function destroy()
    {
        Auth::logout(); //销毁会话
        session()->flash('success','您已经成功退出');
        return redirect('login');   //重定向
    }
}
