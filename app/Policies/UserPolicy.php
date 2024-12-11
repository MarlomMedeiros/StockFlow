<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    public function create(User $user, $newUserData): bool
    {
        if ($user->role === 'manager' && $newUserData['role'] === 'admin') {
            return false;
        }
        return in_array($user->role, ['admin', 'manager']);
    }


    public function update(User $user, User $targetUser): bool
    {
        if ($targetUser->role === 'admin') {
            return $user->id === $targetUser->id;
        }

        return in_array($user->role, ['admin', 'manager']);
    }

    public function delete(User $user, User $targetUser): bool
    {
        if ($targetUser->role === 'admin') {
            return $user->id === $targetUser->id;
        }

        return in_array($user->role, ['admin', 'manager']);
    }
}
