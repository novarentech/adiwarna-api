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

    public function search(?string $keyword): self
    {
        if ($keyword) {
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
                    ->orWhereRaw('JSON_SEARCH(LOWER(scope_of_work), "one", LOWER(?)) IS NOT NULL', ["%{$keyword}%"]);
            });
        }
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

    public function byDateRange(string $startDate, string $endDate): self
    {
        $this->query->whereBetween('date', [$startDate, $endDate]);
        return $this;
    }

    public function sortBy(string $sortOrder = 'desc'): self
    {
        $this->query->orderBy('work_order_year', $sortOrder)
            ->orderBy('work_order_no', $sortOrder);
        return $this;
    }

    public function applySort(?string $sortBy = null): self
    {
        if (!$sortBy) {
            return $this->sortBy('desc');
        }

        if (str_contains($sortBy, ':')) {
            [$field, $direction] = explode(':', $sortBy);
        } else {
            $field = $sortBy;
            $direction = 'asc';
        }

        // Map alias to actual columns or handle relationships
        switch ($field) {
            case 'date_started':
                $this->query->orderBy('date', $direction);
                break;

            case 'customer':
                $this->query->join('customers', 'work_orders.customer_id', '=', 'customers.id')
                    ->orderBy('customers.name', $direction)
                    ->select('work_orders.*');
                break;

            case 'work_location':
                $this->query->join('customer_locations', 'work_orders.customer_location_id', '=', 'customer_locations.id')
                    ->orderBy('customer_locations.location_name', $direction)
                    ->select('work_orders.*');
                break;

            case 'workers_name':
                // Sorting by multiple employees is complex, usually we sort by the first one found
                $this->query->leftJoin('work_order_employees', 'work_orders.id', '=', 'work_order_employees.work_order_id')
                    ->leftJoin('employees', 'work_order_employees.employee_id', '=', 'employees.id')
                    ->groupBy('work_orders.id')
                    ->orderByRaw("MIN(employees.name) $direction")
                    ->select('work_orders.*');
                break;

            default:
                // Fallback to base implementation for direct columns
                parent::applySort($sortBy);
                break;
        }

        return $this;
    }
}
