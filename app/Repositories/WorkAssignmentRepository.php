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

    public function withEmployees(): self
    {
        $this->query->with(['customer', 'customerLocation', 'employees']);
        return $this;
    }
}
