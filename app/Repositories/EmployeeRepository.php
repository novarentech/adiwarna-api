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
}
