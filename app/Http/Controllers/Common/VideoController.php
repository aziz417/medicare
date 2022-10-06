<?php

namespace App\Http\Controllers\Common;

use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Notifications\StartCall;
use App\Http\Controllers\Controller;

class VideoController extends Controller
{
    public function call(Request $request, Appointment $appointment)
    {
        $force = $request->force_token == md5($appointment->doctor_id); // only doctor can start forcefully
        app('debugbar')->disable(); // disabled the debugger
        if( $appointment->userCanJoinRoom(auth()->id()) && (!$appointment->isCompleted() || $force) ){
            if( ($appointment->timeIsApeared() || $force) && !$request->has('user') ){
                if( auth()->id() == $appointment->user_id && $appointment->timeIsApeared() ) {
                    // For user it must be appeared
                    $appointment->doctor->notify(new StartCall($appointment, $force ? $appointment->doctor_id : false));
                }elseif( auth()->id() != $appointment->user_id ){
                    // For doctor no need to check
                    $notifier = $appointment->patient->isSubmember() ? $appointment->user : $appointment->patient;
                    $notifier->notify(new StartCall($appointment, $force ? $appointment->doctor_id : false));
                }
            }
            $provider = $appointment->doctor->getMeta('video_call_provider', 'jitsi');
            return view("common.video.{$provider}", compact('appointment', 'force'));
        }
        return view("common.video.invalid", compact('appointment'));
    }
}
