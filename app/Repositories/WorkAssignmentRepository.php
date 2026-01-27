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

    public function search(?string $keyword): self
    {
        if ($keyword) {
            $this->query->where(function ($query) use ($keyword) {
                $query->whereHas('customer', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                })
                    ->orWhereHas('customerLocation', function ($q) use ($keyword) {
                        $q->where('location_name', 'like', "%{$keyword}%");
                    });
            });
        }
        return $this;
    }

    public function sortBy(string $sortOrder = 'desc'): self
    {
        $this->query->orderBy('assignment_year', $sortOrder)
            ->orderBy('assignment_no', $sortOrder);
        return $this;
    }
}
