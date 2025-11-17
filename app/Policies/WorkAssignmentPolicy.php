<?php

namespace App\Policies;

use App\Enums\UserType;
use App\Models\User;
use App\Models\WorkAssignment;

class WorkAssignmentPolicy
{
    /**
     * Determine if the user can view any work assignments.
     */
    public function viewAny(User $user): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can view the work assignment.
     */
    public function view(User $user, WorkAssignment $workAssignment): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can create work assignments.
     */
    public function create(User $user): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can update the work assignment.
     */
    public function update(User $user, WorkAssignment $workAssignment): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can delete the work assignment.
     */
    public function delete(User $user, WorkAssignment $workAssignment): bool
    {
        return $user->usertype === UserType::ADMIN;
    }
}
