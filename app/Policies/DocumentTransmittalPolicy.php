<?php

namespace App\Policies;

use App\Enums\UserType;
use App\Models\DocumentTransmittal;
use App\Models\User;

class DocumentTransmittalPolicy
{
    /**
     * Determine if the user can view any document transmittals.
     */
    public function viewAny(User $user): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can view the document transmittal.
     */
    public function view(User $user, DocumentTransmittal $documentTransmittal): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can create document transmittals.
     */
    public function create(User $user): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can update the document transmittal.
     */
    public function update(User $user, DocumentTransmittal $documentTransmittal): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can delete the document transmittal.
     */
    public function delete(User $user, DocumentTransmittal $documentTransmittal): bool
    {
        return $user->usertype === UserType::ADMIN;
    }
}
