<?php

namespace App\Contracts\Repositories;

interface PurchaseRequisitionItemRepositoryInterface extends RepositoryInterface
{
    /**
     * Delete items by purchase requisition ID
     */
    public function deleteByPurchaseRequisitionId(int $prId): bool;
}
