<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Appointment;

class CanUseTheChatRoom
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $room = $request->route('room');
        $user = $request->user();
        if( $room instanceof Appointment && $room->userCanJoinRoom($user->id) ){
            return $next($request);
        }
        
        return response()->json([
            'status' => false, 
            'message' => 'Permission Denied!', 
        ]);
    }
}
