<?php

namespace App\Policies;

use App\Enums\UserType;
use App\Models\User;
use App\Models\WorkOrder;

class WorkOrderPolicy
{
    /**
     * Determine if the user can view any work orders.
     */
    public function viewAny(User $user): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can view the work order.
     */
    public function view(User $user, WorkOrder $workOrder): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can create work orders.
     */
    public function create(User $user): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can update the work order.
     */
    public function update(User $user, WorkOrder $workOrder): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can delete the work order.
     */
    public function delete(User $user, WorkOrder $workOrder): bool
    {
        return $user->usertype === UserType::ADMIN;
    }
}
