<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\ApiController;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;


class UserController extends ApiController
{

    public function register ()
    {
        return view('user.register');
    }
    public function store (Request $request)
    {
       $res = User::where('email',$request->input('email'))->first();
       if($res){
           return $this->outPutJson(400,'用户已存在');
       }else{
           $data = User::create([
               'password'=>bcrypt($request->input('password')),
               'email'=>$request->input('email'),
               'name'=>$request->input('email'),
           ]);
           return $this->outPutJson(200,'成功',$data);


       }
    }
    public function login ()
    {
        return view('user.login');
    }
    public function signIn(Request $request)
    {
        $user = User::where('email',$request->input('email'))->first();
        if(!isset($user)){
            return $this->outPutJson(500,'用户不存在');
        }
        $res = Hash::check($request->input('password'),isset($user)?$user->password:'');
        if($res)
        {
            session(['user' => $user->name]);
            return $this->outPutJson(200,'登录成功',$user);
        }else{
            return $this->outPutJson(200,'密码错误',$user);
        }
    }
    public function logout(Request $request)
    {
        $request->session()->pull('user');
        return $this->outPutJson(200,'退出登录');
    }
}