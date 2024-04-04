<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class AdminController extends Controller
{
    // 显示管理员登录表单
    public function showLoginForm()
    {
        return view('Admin.login');
    }

    // 处理管理员登录请求
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            // 登录成功
            return redirect()->intended('/admin/dashboard');
        } else {
            // 登录失败
            return redirect()->route('admin.login')->with('error', 'Invalid credentials');
        }
    }

    // 管理员注销
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
