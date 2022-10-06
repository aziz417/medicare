<?php

namespace App\Http\Middleware;

use Closure;
use App\Contracts\MustVerifyMobile;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class VerifyEmailOrMobileMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        if( !$user ){
            return $request->expectsJson()
                ? abort(403, $message)
                : Redirect::route('login');
        }

        $mobileIsNotVerified = ($user instanceof MustVerifyMobile && !$user->hasVerifiedMobile());
        $emailIsNotVerified = ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail());

        if ( $mobileIsNotVerified && $emailIsNotVerified ) {
            return $this->getResponse($request, 'Your email or mobile is not verified.');
        }

        return $next($request);
    }

    public function getResponse($request, $message = "Unauthorized user!")
    {
        return $request->expectsJson()
            ? abort(403, $message)
            : Redirect::route('auth.verify');
    }
}
