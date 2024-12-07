<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view all models.
     */
    public function viewAll(User $user): bool
    {
        return $user->hasAnyAbility(['users:ro', 'users:rw']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return ($user->is($model) && $user->id == $model->id) || $user->hasAnyAbility(['users:ro', 'users:rw']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAbility('users:rw');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->hasAbility('users:rw');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->hasAbility('users:rw');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user): bool
    {
        return $user->hasAbility('users:rw');
    }
}
