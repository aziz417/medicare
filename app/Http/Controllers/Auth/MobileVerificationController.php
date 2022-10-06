<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Events\Auth\MobileVerified;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Auth\Access\AuthorizationException;

class MobileVerificationController extends Controller
{
    use RedirectsUsers;
    
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Show the mobile verification notice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    public function showNotice(Request $request)
    {
        if( ! $request->user()->requiredVerification('mobile') ){
            return redirect($this->redirectPath());
        }
        return ($request->user()->hasVerifiedMobile())
                        ? redirect($this->redirectPath())
                        : view('auth.verify-mobile');
    }

    /**
     * Mark the authenticated user's mobile number as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request)
    {
        if ($request->user()->hasVerifiedMobile()) {
            return $request->wantsJson()
                        ? new Response('', 204)
                        : redirect($this->redirectPath());
        }

        $request->validate([
            'otp' => ['required','string', 'min:4', 'max:6', Rule::in([$request->user()->getSavedOtp()])]
        ], [
            'otp.in' => "Invalid verification code!"
        ]);

        if ( $request->user()->markMobileAsVerified()) {
            event(new MobileVerified($request->user()));
        }

        return $request->wantsJson()
                    ? new Response('', 204)
                    : redirect($this->redirectPath())->with('verified', true);
    }

    /**
     * Resend the mobile verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedMobile()) {
            return $request->wantsJson()
                        ? new Response('', 204)
                        : redirect($this->redirectPath());
        }

        $request->user()->sendMobileVerificationNotification();

        return $request->wantsJson()
                    ? new Response('', 202)
                    : back()->with('resent-code', true);
    }
}
