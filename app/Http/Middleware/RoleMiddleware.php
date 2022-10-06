<?php

namespace App\Http\Middleware;

use Closure;
use App\Providers\RouteServiceProvider;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        // NOTE: Need to implement hasPermission 
        if( $user = $request->user() ){
            $role = pipe2array($role);
            $isMaster = ( $user->isRole('master') && !in_array($role, ['user', 'patient']) );
            if( $user->isRole($role) || $isMaster ){
                return $next($request);
            }else{
                return redirect(RouteServiceProvider::HOME);
            }
        }
        return redirect()->route('login');
    }

    public function inRole($request)
    {
        return $request->user()->isRole([
            'master', 'admin', 'doctor', 'patient', 'user'
        ]);
    }
}
