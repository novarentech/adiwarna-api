<?php

namespace App\Repositories;

use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Models\Customer;

class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{
    protected function model(): string
    {
        return Customer::class;
    }

    public function withLocations(): self
    {
        $this->query->with('locations');
        return $this;
    }
}
