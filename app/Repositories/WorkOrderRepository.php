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
        $this->query->with(['employees', 'customer', 'customerLocation']);
        return $this;
    }

    public function withCustomerAndLocation(): self
    {
        $this->query->with(['employees', 'customer', 'customerLocation']);
        return $this;
    }

    public function search(string $keyword): self
    {
        $this->query->where(function ($query) use ($keyword) {
            $query->whereHas('employees', function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%");
            })
                ->orWhereHas('customer', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                })
                ->orWhereHas('customerLocation', function ($q) use ($keyword) {
                    $q->where('location_name', 'like', "%{$keyword}%");
                })
                ->orWhereRaw('JSON_SEARCH(scope_of_work, "one", ?) IS NOT NULL', ["%{$keyword}%"]);
        });
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
