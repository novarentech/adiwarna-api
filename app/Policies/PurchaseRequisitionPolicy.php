<?php

namespace App\Policies;

use App\Enums\UserType;
use App\Models\PurchaseRequisition;
use App\Models\User;

class PurchaseRequisitionPolicy
{
    /**
     * Determine if the user can view any purchase requisitions.
     */
    public function viewAny(User $user): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can view the purchase requisition.
     */
    public function view(User $user, PurchaseRequisition $purchaseRequisition): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can create purchase requisitions.
     */
    public function create(User $user): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can update the purchase requisition.
     */
    public function update(User $user, PurchaseRequisition $purchaseRequisition): bool
    {
        return $user->usertype === UserType::ADMIN;
    }

    /**
     * Determine if the user can delete the purchase requisition.
     */
    public function delete(User $user, PurchaseRequisition $purchaseRequisition): bool
    {
        return $user->usertype === UserType::ADMIN;
    }
}
