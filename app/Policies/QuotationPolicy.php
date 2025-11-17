<?php

namespace App\Policies;

use App\Enums\UserType;
use App\Models\Quotation;
use App\Models\User;

class QuotationPolicy
{
    /**
     * Determine if the user can view any quotations.
     */
    public function viewAny(User $user): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can view the quotation.
     */
    public function view(User $user, Quotation $quotation): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can create quotations.
     */
    public function create(User $user): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can update the quotation.
     */
    public function update(User $user, Quotation $quotation): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can delete the quotation.
     */
    public function delete(User $user, Quotation $quotation): bool
    {
        return $user->usertype === UserType::ADMIN;
    }
}
