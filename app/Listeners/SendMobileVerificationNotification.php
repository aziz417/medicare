<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use App\Contracts\MustVerifyMobile;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMobileVerificationNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        if ($event->user instanceof MustVerifyMobile && ! $event->user->hasVerifiedMobile()) {
            // $event->user->sendMobileVerificationNotification();
            if( $event->user->isRole(['user', 'patient']) ){
                $event->user->sendMobileVerificationNotification();
            }
        }
    }
}
