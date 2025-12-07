<?php

namespace App\Policies;

use App\Models\Business;
use App\Models\User;

class BusinessPolicy
{
    /**
     * Determine whether the user can view any models.
     * Public access - everyone can view business list.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     * Public access - everyone can view business details.
     */
    public function view(?User $user, Business $business): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     * Only authenticated users (admin, student, alumni) can create.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'student', 'alumni']);
    }

    /**
     * Determine whether the user can update the model.
     * Allow if user is admin OR the owner of the business.
     */
    public function update(User $user, Business $business): bool
    {
        return $user->role === 'admin' || $user->id === $business->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     * Allow if user is admin OR the owner of the business.
     */
    public function delete(User $user, Business $business): bool
    {
        return $user->role === 'admin' || $user->id === $business->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Business $business): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Business $business): bool
    {
        return $user->role === 'admin';
    }
}
