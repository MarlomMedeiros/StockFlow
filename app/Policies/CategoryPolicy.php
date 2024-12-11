<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['common_user', 'admin', 'manager']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    public function update(User $user, Category $category): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    public function delete(User $user, Category $category): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }
}
