<?php

namespace App\Services;

use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Models\Customer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CustomerService extends BaseService
{
    public function __construct(
        protected CustomerRepositoryInterface $customerRepository
    ) {}

    public function getAllCustomers(): Collection
    {
        return $this->customerRepository->all();
    }

    public function getPaginatedCustomers(int $perPage = 15): LengthAwarePaginator
    {
        return $this->customerRepository->paginate($perPage);
    }

    public function getCustomerById(int $id): ?Customer
    {
        return $this->customerRepository->with(['locations'])->find($id);
    }

    public function createCustomer(array $data): Customer
    {
        return $this->customerRepository->create($data);
    }

    public function updateCustomer(int $id, array $data): Customer
    {
        return $this->customerRepository->update($id, $data);
    }

    public function deleteCustomer(int $id): bool
    {
        return $this->customerRepository->delete($id);
    }
}
