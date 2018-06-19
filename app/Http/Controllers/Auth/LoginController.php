<?php

namespace Inggo\Boilerplate\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Inggo\Boilerplate\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Inggo\Boilerplate\User;

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
    protected $redirectTo = null;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            $user = Auth::user();
            $success['token'] = $user->createToken(config('app.name'))->accessToken;
            $success['user'] = $user;
            return response()->json($success, 200);
        }

        return $this->sendFailedLoginResponse($request);
    }

    public function username()
    {
        return filter_var(request()->username, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';
    }
}
