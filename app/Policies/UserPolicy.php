<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Only admin can view user list
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Only admin can view specific user
     */
    public function view(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    /**
     * Only admin can create users
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Only admin can update users
     */
    public function update(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    /**
     * Only admin can delete users
     */
    public function delete(User $user, User $model): bool
    {
        return $user->isAdmin();
    }
}