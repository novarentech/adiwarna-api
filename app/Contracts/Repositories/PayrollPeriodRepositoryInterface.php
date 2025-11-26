<?php

namespace App\Contracts\Repositories;

interface PayrollPeriodRepositoryInterface extends RepositoryInterface
{
    public function withEmployees(): self;
}
