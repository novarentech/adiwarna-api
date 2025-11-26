<?php

namespace App\Policies;

use App\Enums\UserType;
use App\Models\User;

class TrackRecordPolicy
{
    /**
     * Determine if the user can view any track records.
     */
    public function viewAny(User $user): bool
    {
        return $user->usertype === UserType::ADMIN;
    }
}
