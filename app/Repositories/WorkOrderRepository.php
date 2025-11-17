<?php

namespace App\Repositories;

use App\Contracts\Repositories\WorkOrderRepositoryInterface;
use App\Models\WorkOrder;
use Illuminate\Database\Eloquent\Collection;

class WorkOrderRepository extends BaseRepository implements WorkOrderRepositoryInterface
{
    protected function model(): string
    {
        return WorkOrder::class;
    }

    public function withRelations(): self
    {
        $this->query->with(['employees', 'customer']);
        return $this;
    }

    public function byCustomer(int $customerId): Collection
    {
        return $this->model->where('customer_id', $customerId)->get();
    }

    public function byStatus(string $status): Collection
    {
        return $this->model->where('status', $status)->get();
    }
}
