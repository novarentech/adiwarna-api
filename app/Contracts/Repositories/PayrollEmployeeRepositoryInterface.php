<?php

namespace App\Contracts\Repositories;

interface PayrollEmployeeRepositoryInterface extends RepositoryInterface
{
    public function withTimesheets(): self;
}
