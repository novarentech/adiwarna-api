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

    public function getPaginatedCustomers(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->customerRepository->withLocations();

        if ($search) {
            $query->search($search);
        }

        return $query->paginate($perPage);
    }

    public function getCustomerById(int $id): ?Customer
    {
        return $this->customerRepository->with(['locations'])->find($id);
    }

    public function createCustomer(array $data): Customer
    {
        return $this->executeInTransaction(function () use ($data) {
            $customerData = array_diff_key($data, array_flip(['customer_locations']));
            $customer = $this->customerRepository->create($customerData);

            if (isset($data['customer_locations'])) {
                foreach ($data['customer_locations'] as $location) {
                    $customer->locations()->create($location);
                }
            }

            return $customer->load('locations');
        });
    }

    public function updateCustomer(int $id, array $data): Customer
    {
        return $this->executeInTransaction(function () use ($id, $data) {
            $customerData = array_diff_key($data, array_flip(['customer_locations']));
            $customer = $this->customerRepository->update($id, $customerData);

            if (isset($data['customer_locations'])) {
                $existingLocationIds = [];

                foreach ($data['customer_locations'] as $locationData) {
                    if (isset($locationData['id']) && $locationData['id']) {
                        // Update existing location
                        $location = $customer->locations()->find($locationData['id']);
                        if ($location) {
                            $location->update($locationData);
                            $existingLocationIds[] = $locationData['id'];
                        }
                    } else {
                        // Create new location
                        $newLocation = $customer->locations()->create($locationData);
                        $existingLocationIds[] = $newLocation->id;
                    }
                }

                // Delete locations that are not in the request
                $customer->locations()->whereNotIn('id', $existingLocationIds)->delete();
            }

            return $customer->load('locations');
        });
    }

    public function deleteCustomer(int $id): bool
    {
        return $this->customerRepository->delete($id);
    }
}
