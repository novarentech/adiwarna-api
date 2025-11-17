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

    public function byDateRange(string $startDate, string $endDate): Collection
    {
        return $this->model
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();
    }
}
