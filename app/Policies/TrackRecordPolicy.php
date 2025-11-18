<?php

namespace App\Policies;

use App\Enums\UserType;
use App\Models\TrackRecord;
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

    /**
     * Determine if the user can view the track record.
     */
    public function view(User $user, TrackRecord $trackRecord): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can create track records.
     */
    public function create(User $user): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can update the track record.
     */
    public function update(User $user, TrackRecord $trackRecord): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can delete the track record.
     */
    public function delete(User $user, TrackRecord $trackRecord): bool
    {
        return $user->usertype === UserType::ADMIN;
    }
}
