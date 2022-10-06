<?php

namespace App\Policies;

use App\Models\Prescription;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrescriptionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Prescription  $prescription
     * @return mixed
     */
    public function delete(User $user, Prescription $prescription)
    {
        if( $prescription->doctor_id == $user->id ){
            return $user->isAdmin();
        }
        return false;
    }

}
