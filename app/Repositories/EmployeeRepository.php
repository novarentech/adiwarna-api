<?php

namespace App\Repositories;

use App\Contracts\Repositories\EmployeeRepositoryInterface;
use App\Models\Employee;

class EmployeeRepository extends BaseRepository implements EmployeeRepositoryInterface
{
    protected function model(): string
    {
        return Employee::class;
    }

    public function search(string $keyword): self
    {
        $this->query->where(function ($query) use ($keyword) {
            $query->where('name', 'like', "%{$keyword}%")
                ->orWhere('position', 'like', "%{$keyword}%");
        });
        return $this;
    }
}
