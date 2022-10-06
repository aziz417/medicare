<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
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

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $username = filter_var($request->get($this->username()), FILTER_VALIDATE_EMAIL)
                ? $this->username()
                : 'mobile';
        return [
            $username => $this->validateUsername($request->get($this->username()), $username),
            'password' => $request->get('password'),
            'status' => 'active' // Checking the user status
        ];
        return $request->only($this->username(), 'password');
    }

    public function validateUsername($value, $type)
    {
        if( $type == 'mobile' ){
            return validateMobile($value);
        }
        return $value;
    }

    public function authenticated(Request $request, $user)
    {
        if( config('auth.refresh_token', false) || empty($user->api_token) ){
            $user->updateApiToken();
        }
        if( $user->isRole(['master', 'admin', 'doctor']) ){
            return redirect()->route('admin.home');
        }else{
            return redirect()->route('user.home');
        }
        return redirect()->route('home');
    }
}
