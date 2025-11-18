<?php

namespace App\Policies;

use App\Enums\UserType;
use App\Models\Schedule;
use App\Models\User;

class SchedulePolicy
{
    /**
     * Determine if the user can view any schedules.
     */
    public function viewAny(User $user): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can view the schedule.
     */
    public function view(User $user, Schedule $schedule): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can create schedules.
     */
    public function create(User $user): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can update the schedule.
     */
    public function update(User $user, Schedule $schedule): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can delete the schedule.
     */
    public function delete(User $user, Schedule $schedule): bool
    {
        return $user->usertype === UserType::ADMIN;
    }
}
