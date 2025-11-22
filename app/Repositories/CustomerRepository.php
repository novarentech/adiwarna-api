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

    public function search(string $keyword): self
    {
        $this->query->where(function ($query) use ($keyword) {
            $query->where('name', 'like', "%{$keyword}%")
                ->orWhere('address', 'like', "%{$keyword}%")
                ->orWhere('phone_number', 'like', "%{$keyword}%")
                ->orWhereHas('locations', function ($q) use ($keyword) {
                    $q->where('location_name', 'like', "%{$keyword}%");
                });
        });
        return $this;
    }
}
