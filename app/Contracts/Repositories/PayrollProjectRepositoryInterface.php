<?php

namespace App\Contracts\Repositories;

interface PayrollProjectRepositoryInterface extends RepositoryInterface
{
    public function withPeriods(): self;

    public function search(?string $search = null): self;
}
