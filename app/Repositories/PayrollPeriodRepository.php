<?php

namespace App\Repositories;

use App\Contracts\Repositories\PayrollPeriodRepositoryInterface;
use App\Models\PayrollPeriod;

class PayrollPeriodRepository extends BaseRepository implements PayrollPeriodRepositoryInterface
{
    protected function model(): string
    {
        return PayrollPeriod::class;
    }

    public function withEmployees(): self
    {
        $this->query->with('employees');
        return $this;
    }

    public function withEmployeesCount(): self
    {
        $this->query->withCount('employees');
        return $this;
    }
}
