<?php

namespace App\Policies;

use App\Enums\UserType;
use App\Models\User;

class UserPolicy
{
    /**
     * Determine if the user can view any users.
     * Only admin can manage users.
     */
    public function viewAny(User $user): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can view the user.
     * Only admin can view users.
     */
    public function view(User $user, User $model): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can create users.
     * Only admin can create users.
     */
    public function create(User $user): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can update the user.
     * Only admin can update users.
     */
    public function update(User $user, User $model): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can delete the user.
     * Only admin can delete users, but cannot delete themselves.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->usertype === UserType::ADMIN && $user->id !== $model->id;
    }
}
