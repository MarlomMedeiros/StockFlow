<?php

namespace App\Policies;

use App\Models\User;

class ProductPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['common_user', 'admin', 'manager']);
    }

    public function view(User $user): bool
    {
        return in_array($user->role, ['common_user', 'admin', 'manager']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    public function update(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    public function delete(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }
}
