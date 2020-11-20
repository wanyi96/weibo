<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Mail;
use Auth;

class UserController extends Controller
{
    public function __construct()
    {
        //除了下面几个操作，其他方法都要先登录才能访问
        //未登录用户有权限访问的方法
        $this->middleware('auth',[
            'except'=>['show','create','store','index','confirmEmail']
        ]);
        //只让未登录用户访问注册页面
        $this->middleware('guest',[
            'only'=>['create']
        ]);
    }

    public function index()
    {
        $users = User::paginate(10);
        return view('users.index',compact('users'));
    }

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
        // Auth::login($user);
        // session()->flash('success','欢迎，您将在这里开启一段新的旅程~');
        // return redirect()->route('users.show',[$user]);

        $this->sendEmailConfirmationTo($user);
        session()->flash('success','验证邮件已经发送到你的注册邮箱上，请注意查收');
        return redirect('/');   //返回首页
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit',compact('user'));
    }

    public function update(User $user,Request $request)
    {
        $this->authorize('update', $user);
        $this->validate($request,[
            'name'=>'required|max:50',
            'password'=>'nullable|confirmed|min:6',
        ]);

        $data = [];
        $data['name'] = $request->name;
        if($request->password){
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        session()->flash('success','个人资料更新成功');
        return redirect()->route('users.show',$user->id);
    }

    public function destroy(User $user)
    {
        $user->delete();
        session()->flash('success','成功删除用户！');
        return back();  //删除后返回之前的页面，即用户列表页面
    }

    public function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        $from = 'summer@example.com';
        $name = 'Summer';
        $to = $user->email;
        $subject = '感谢注册 weibo 应用！，请确认你的邮箱';
        Mail::send($view,$data,function($message) use ($to,$subject){
            $message->to($to)->subject($subject);
        });
    }

    public function confirmEmail($token)
    {
        $user = User::where('activation_token',$token)->firstOrFail();

        $user->activated = true;
        $user->activation_token = null;  //令牌清空
        $user->save();

        Auth::login($user);
        session()->flash('success','恭喜你，激活成功！');
        return redirect()->route('users.show',[$user]);
    }
}
