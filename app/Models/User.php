<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
class User extends Authenticatable
{
    //Authenticatable 是授权相关功能的引用
    use Notifiable;  //消息通知相关功能引用

    /**
     * The attributes that are mass assignable.
     * 防止批量赋值安全漏洞的字段白名单
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     * 对敏感信息进行隐藏
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $table = 'users';


    public function gravatar($size = '100')
    {
        //传递用户邮箱
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function($user){
            //在创建用户之前，就给用户创建好一个长度为10的token令牌
            $user->activation_token = Str::random(10);
        });
    }









}
