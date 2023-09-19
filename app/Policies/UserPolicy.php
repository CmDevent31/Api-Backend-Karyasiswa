<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }


public function update(User $user, User $userToUpdate)
{
    return $user->id === $userToUpdate->id; // Atur izin sesuai kebutuhan Anda
}


}
