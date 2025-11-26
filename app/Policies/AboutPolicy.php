<?php

namespace App\Policies;

use App\Enums\UserType;
use App\Models\About;
use App\Models\User;

class AboutPolicy
{
    /**
     * Determine if the user can view any company information.
     */
    public function viewAny(User $user): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can view the company information.
     */
    public function view(User $user, About $about): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can create company information.
     */
    public function create(User $user): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can update the company information.
     */
    public function update(User $user, About $about): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can delete the company information.
     */
    public function delete(User $user, About $about): bool
    {
        return $user->usertype === UserType::ADMIN;
    }
}
