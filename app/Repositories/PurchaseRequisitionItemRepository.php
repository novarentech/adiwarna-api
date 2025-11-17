<?php

namespace App\Repositories;

use App\Contracts\Repositories\PurchaseRequisitionItemRepositoryInterface;
use App\Models\PurchaseRequisitionItem;

class PurchaseRequisitionItemRepository extends BaseRepository implements PurchaseRequisitionItemRepositoryInterface
{
    protected function model(): string
    {
        return PurchaseRequisitionItem::class;
    }

    public function deleteByPurchaseRequisitionId(int $prId): bool
    {
        return $this->model->where('purchase_requisition_id', $prId)->delete();
    }
}
