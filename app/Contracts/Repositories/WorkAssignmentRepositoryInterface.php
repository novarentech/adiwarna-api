<?php

namespace App\Contracts\Repositories;

interface WorkAssignmentRepositoryInterface extends RepositoryInterface
{
    public function withEmployees(): self;
}
