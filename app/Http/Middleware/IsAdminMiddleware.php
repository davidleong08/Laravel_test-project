<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::user()->is_admin) { // 假设 `is_admin` 是标记管理员的属性
            // 如果用户不是管理员，您可以选择重定向到首页或其他页面
            return redirect('/home');
        }

        return $next($request);
    }
}
