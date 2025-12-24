<?php

namespace App\Repositories;

use App\Contracts\Repositories\PurchaseRequisitionRepositoryInterface;
use App\Models\PurchaseRequisition;
use Illuminate\Database\Eloquent\Collection;

class PurchaseRequisitionRepository extends BaseRepository implements PurchaseRequisitionRepositoryInterface
{
    protected function model(): string
    {
        return PurchaseRequisition::class;
    }

    public function withItems(): self
    {
        $this->query->with('items');
        return $this;
    }

    public function search(?string $search = null): self
    {
        if ($search) {
            $this->query->where(function ($query) use ($search) {
                $query->where('pr_no', 'like', "%{$search}%")
                    ->orWhere('supplier', 'like', "%{$search}%")
                    ->orWhere('place_of_delivery', 'like', "%{$search}%");
            });
        }
        return $this;
    }

    public function byDateRange(string $startDate, string $endDate): Collection
    {
        return $this->model
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();
    }
}
