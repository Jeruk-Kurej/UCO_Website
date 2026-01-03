<?php

namespace App\Policies;

use App\Models\Business;
use App\Models\User;

class BusinessPolicy
{
    /*
    |--------------------------------------------------------------------------
    | View Policies
    |--------------------------------------------------------------------------
    */

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Business $business): bool
    {
        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | Mutation Policies
    |--------------------------------------------------------------------------
    */

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'student', 'alumni']);
    }

    public function update(User $user, Business $business): bool
    {
        return $user->role === 'admin' || $user->id === $business->user_id;
    }

    public function delete(User $user, Business $business): bool
    {
        return $user->role === 'admin' || $user->id === $business->user_id;
    }

    public function restore(User $user, Business $business): bool
    {
        return $user->role === 'admin';
    }

    public function forceDelete(User $user, Business $business): bool
    {
        return $user->role === 'admin';
    }
}
