<?php

namespace App\Http\Middleware;

use Closure;
use App\Contracts\MustVerifyMobile;
use Illuminate\Support\Facades\Redirect;

class EnsureMobileIsVerified
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
        if (! $request->user() ||
            ($request->user() instanceof MustVerifyMobile &&
            ! $request->user()->hasVerifiedMobile())) {
            return $request->expectsJson()
                    ? abort(403, 'Your mobile address is not verified.')
                    : Redirect::route('verification.mobile.notice');
        }

        return $next($request);
    }
}
