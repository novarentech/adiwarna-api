<?php

namespace App\Repositories;

use App\Contracts\Repositories\PayrollEmployeeRepositoryInterface;
use App\Models\PayrollEmployee;

class PayrollEmployeeRepository extends BaseRepository implements PayrollEmployeeRepositoryInterface
{
    protected function model(): string
    {
        return PayrollEmployee::class;
    }

    public function withTimesheets(): self
    {
        $this->query->with('timesheets');
        return $this;
    }
}
