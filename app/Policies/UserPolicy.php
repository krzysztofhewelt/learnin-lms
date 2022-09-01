<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    // everyone can show user profile only
    // admin can edit and create user

    public function createUpdateDelete(User $user) : bool
    {
        return $user->isAdmin();
    }

    public function updateTeacher(User $user, User $updatedUser) : bool
    {
        return ($user->isTeacher() && $updatedUser->id === $user->id);
    }
}
