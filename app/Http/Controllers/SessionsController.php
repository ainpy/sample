<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    public function __construct()
    {
        // 只有未登陆状态下才可以访问create
        $this->middleware('guest', [
           'only'  =>  ['create'],
        ]);
    }

    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {
        $credentials = $this->validate($request, [
            'email' =>  'required|email|max:255',
            'password'  =>  'required'
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            //登录操作, 验证是否激活邮件,
            if (Auth::user()->activated) {
                session()->flash('success', '欢迎回来~');
                return redirect()->intended(route('users.show', [Auth::user()]));
            } else {
                Auth::logout();
                session()->flash('warning', '账号未激活!');
                return redirect('/');
            }

        } else {
            //登录失败的操作
            session()->flash('danger', '很抱歉,您的邮箱或密码错误');
            return redirect()->back();
        }
    }

    public function destroy()
    {
        Auth::logout();
        session()->flash('success','您已成功退出!');
        return redirect('login');
    }
}
