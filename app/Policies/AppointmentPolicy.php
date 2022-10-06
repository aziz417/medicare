<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppointmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Appointment  $appointment
     * @return mixed
     */
    public function startCall(User $user, Appointment $appointment)
    {
        return $user->id == $appointment->user_id || $user->id == $appointment->doctor_id ;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Appointment  $appointment
     * @return mixed
     */
    public function update(User $user, Appointment $appointment)
    {
        if( $appointment->isConfirmed() ){
            return $user->isSuperAdmin();
        }
        return (!$appointment->isConfirmed() && $user->isAdmin(false)) || ($appointment->doctor_id == $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Appointment  $appointment
     * @return mixed
     */
    public function delete(User $user, Appointment $appointment)
    {
        if( $appointment->isConfirmed() ){
            return $user->isSuperAdmin();
        }
        return $user->isAdmin(false) || (!$appointment->isConfirmed() && $appointment->doctor_id == $user->id);
    }

}
