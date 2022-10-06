<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Contracts\MustVerifyMobile;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
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
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Show the email verification notice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show(Request $request)
    {
        if( ! $request->user()->requiredVerification('email') ){
            return redirect($this->redirectPath());
        }
        return $request->user()->hasVerifiedEmail()
                        ? redirect($this->redirectPath())
                        : view('auth.verify-email');
    }

    public function showVerifyPage(Request $request)
    {
        $user = $request->user();
        $mobileIsVerified = ($user instanceof MustVerifyMobile && $user->hasVerifiedMobile());
        $emailIsVerified = ($user instanceof MustVerifyEmail && $user->hasVerifiedEmail());

        if( $mobileIsVerified || $emailIsVerified ){
            return redirect($this->redirectPath());
        }
        return view('auth.verify');
    }
}
