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
     * Admin can view any user, or users can view themselves
     */
    public function view(User $user, User $model): bool
    {
        // Admin can view anyone, or user can view themselves
        return $user->isAdmin() || $user->id === $model->id;
    }

    /**
     * Only admin can create users
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Only admin can update users (cannot edit themselves via this route)
     */
    public function update(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    /**
     * Only admin can delete users (but cannot delete themselves)
     */
    public function delete(User $user, User $model): bool
    {
        // Admin can delete, but NOT themselves
        return $user->isAdmin() && $user->id !== $model->id;
    }

    /**
     * Prevent user from deleting themselves
     */
    public function deleteSelf(User $user, User $model): bool
    {
        return false; // Never allow deleting yourself via admin panel
    }
}