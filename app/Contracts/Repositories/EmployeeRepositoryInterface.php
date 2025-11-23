<?php

namespace App\Contracts\Repositories;

interface EmployeeRepositoryInterface extends RepositoryInterface
{
    /**
     * Search employees by name or position
     */
    public function search(string $keyword): self;
}
