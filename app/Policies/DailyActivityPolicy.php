<?php

namespace App\Policies;

use App\Enums\UserType;
use App\Models\DailyActivity;
use App\Models\User;

class DailyActivityPolicy
{
    /**
     * Determine if the user can view any daily activities.
     * Admin can view all, Teknisi can view their own.
     */
    public function viewAny(User $user): bool
    {
        return $user->usertype === UserType::ADMIN || $user->usertype === UserType::TEKNISI;
    }

    /**
     * Determine if the user can view the daily activity.
     * Admin can view all, Teknisi can only view their own.
     */
    public function view(User $user, DailyActivity $dailyActivity): bool
    {
        if ($user->usertype === UserType::ADMIN) {
            return true;
        }

        // Teknisi can only view activities they are part of
        if ($user->usertype === UserType::TEKNISI) {
            // Check if user is in the members list
            return $dailyActivity->members()
                ->where('name', $user->name)
                ->exists();
        }

        return false;
    }

    /**
     * Determine if the user can create daily activities.
     */
    public function create(User $user): bool
    {
        return $user->usertype === UserType::ADMIN || $user->usertype === UserType::TEKNISI;
    }

    /**
     * Determine if the user can update the daily activity.
     * Admin can update all, Teknisi can only update their own.
     */
    public function update(User $user, DailyActivity $dailyActivity): bool
    {
        if ($user->usertype === UserType::ADMIN) {
            return true;
        }

        // Teknisi can only update activities they are part of
        if ($user->usertype === UserType::TEKNISI) {
            return $dailyActivity->members()
                ->where('name', $user->name)
                ->exists();
        }

        return false;
    }

    /**
     * Determine if the user can delete the daily activity.
     * Only admin can delete.
     */
    public function delete(User $user, DailyActivity $dailyActivity): bool
    {
        return $user->usertype === UserType::ADMIN;
    }
}
