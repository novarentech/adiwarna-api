<?php

namespace App\Contracts\Repositories;

interface CustomerRepositoryInterface extends RepositoryInterface
{
    public function withLocations(): self;
}
