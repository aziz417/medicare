<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $auth
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function updateDoctor(User $auth, User $user)
    {
        return ($user->isAdmin(false) && $auth->id !== $user->id) || $auth->is_desk_doctor == 1;
    }
    
    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $auth
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function deleteDoctor(User $auth, User $user)
    {
        return $user->isAdmin(false) && $auth->id !== $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $auth
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function delete(User $auth, User $user)
    {
        if($user->isAdmin()){
            return $auth->isSuperAdmin() && $auth->id !== $user->id;
        }
        return $user->isAdmin() && $auth->id !== $user->id;
    }
}
