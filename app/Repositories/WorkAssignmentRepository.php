<?php

namespace App\Repositories;

use App\Contracts\Repositories\WorkAssignmentRepositoryInterface;
use App\Models\WorkAssignment;

class WorkAssignmentRepository extends BaseRepository implements WorkAssignmentRepositoryInterface
{
    protected function model(): string
    {
        return WorkAssignment::class;
    }

    public function withWorkers(): self
    {
        $this->query->with(['customer', 'customerLocation', 'workers']);
        return $this;
    }

    public function withCustomerOnly(): self
    {
        $this->query->with(['customer', 'customerLocation']);
        return $this;
    }

    public function search(string $keyword): self
    {
        $this->query->where(function ($query) use ($keyword) {
            $query->whereHas('customer', function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%");
            })
                ->orWhereHas('customerLocation', function ($q) use ($keyword) {
                    $q->where('location_name', 'like', "%{$keyword}%")
                        ->orWhere('address', 'like', "%{$keyword}%")
                        ->orWhere('city', 'like', "%{$keyword}%");
                });
        });
        return $this;
    }
}
