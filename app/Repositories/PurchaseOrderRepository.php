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

    public function withCustomerOnly(): self
    {
        $this->query->with(['customer']);
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

    public function search(?string $keyword): self
    {
        if ($keyword) {
            $this->query->where(function ($query) use ($keyword) {
                $query->whereHas('customer', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                })
                    ->orWhere('pic_name', 'like', "%{$keyword}%")
                    ->orWhere('pic_phone', 'like', "%{$keyword}%");
            });
        }
        return $this;
    }

    public function sortBy(string $sortOrder = 'desc'): self
    {
        $this->query->orderBy('po_year', $sortOrder)
            ->orderBy('po_no', $sortOrder);
        return $this;
    }
}
