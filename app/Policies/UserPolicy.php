<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the user.
     *
     * @param User $signedInUser
     * @param  \App\User $user
     * @return mixed
     */
    public function update(User $signedInUser, User $user)
    {
        return $signedInUser->id === $user->user_id;
    }
}
