<?php

namespace App\Contracts\Repositories;

interface WorkAssignmentRepositoryInterface extends RepositoryInterface
{
    public function withWorkers(): self;
    public function withCustomerOnly(): self;
    public function search(string $keyword): self;
}
