<?php

namespace App\Repositories;

use App\Contracts\Repositories\PurchaseOrderRepositoryInterface;
use App\Models\PurchaseOrder;

class PurchaseOrderRepository extends BaseRepository implements PurchaseOrderRepositoryInterface
{
    protected function model(): string
    {
        return PurchaseOrder::class;
    }

    public function withRelations(): self
    {
        $this->query->with(['customer', 'items']);
        return $this;
    }

    public function filterByCustomer(int $customerId): self
    {
        $this->query->where('customer_id', $customerId);
        return $this;
    }

    public function filterByDateRange(string $startDate, string $endDate): self
    {
        $this->query->whereBetween('date', [$startDate, $endDate]);
        return $this;
    }
}
