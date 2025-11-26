<?php

namespace App\Policies;

use App\Enums\UserType;
use App\Models\Operational;
use App\Models\User;

class OperationalPolicy
{
    /**
     * Determine if the user can view any operational records.
     */
    public function viewAny(User $user): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can view the operational record.
     */
    public function view(User $user, Operational $operational): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can create operational records.
     */
    public function create(User $user): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can update the operational record.
     */
    public function update(User $user, Operational $operational): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can delete the operational record.
     */
    public function delete(User $user, Operational $operational): bool
    {
        return $user->usertype === UserType::ADMIN;
    }
}
