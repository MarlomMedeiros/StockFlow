<?php

namespace App\Policies;

use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StockMovementPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'user' || in_array($user->role, ['common_user', 'admin', 'manager']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    public function viewAnyStockMovement(User $user): bool
    {
        return $user->role === 'user' || in_array($user->role, ['common_user', 'admin', 'manager']);
    }

    public function viewOwn(User $user, StockMovement $stockMovement): bool
    {
        return $user->id === $stockMovement->user_id;
    }
}
