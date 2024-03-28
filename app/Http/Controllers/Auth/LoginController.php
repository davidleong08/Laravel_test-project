<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request; // 確保使用這個
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username'; // 指定认证时使用的字段
    }

    protected function authenticated(Request $request, $user)
    {
        $request->session()->flash('status', 'Login successful!');
        return redirect()->intended(route('bodycondition.index')); // 重定向至 bodycondition/index
    }
}
