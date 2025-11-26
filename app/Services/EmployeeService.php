<?php

namespace App\Services;

use App\Contracts\Repositories\EmployeeRepositoryInterface;
use App\Models\Employee;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EmployeeService extends BaseService
{
    public function __construct(
        protected EmployeeRepositoryInterface $employeeRepository
    ) {}

    public function getPaginatedEmployees(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->employeeRepository;

        if ($search) {
            $query->search($search);
        }

        return $query->paginate($perPage);
    }

    public function getEmployeeById(int $id): ?Employee
    {
        return $this->employeeRepository->find($id);
    }

    public function createEmployee(array $data): Employee
    {
        return $this->employeeRepository->create($data);
    }

    public function updateEmployee(int $id, array $data): Employee
    {
        return $this->employeeRepository->update($id, $data);
    }

    public function deleteEmployee(int $id): bool
    {
        return $this->employeeRepository->delete($id);
    }
}
