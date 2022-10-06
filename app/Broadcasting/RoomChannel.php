<?php

namespace App\Broadcasting;

use App\Models\User;
use App\Models\Appointment;

class RoomChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Appointment  $room
     * @return array|bool
     */
    public function join(User $user, Appointment $room)
    {
        if( $room->userCanJoinRoom($user->id) ){
            return $user->getPublicData();
        }
        return false;
    }
}
