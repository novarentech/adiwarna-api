<?php

namespace App\Services;

use App\Contracts\Repositories\PayrollEmployeeRepositoryInterface;
use App\Models\PayrollEmployee;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PayrollEmployeeService extends BaseService
{
    public function __construct(
        protected PayrollEmployeeRepositoryInterface $payrollEmployeeRepository
    ) {}

    public function getPaginatedEmployees(int $perPage = 15): LengthAwarePaginator
    {
        return $this->payrollEmployeeRepository->paginate($perPage);
    }

    public function getEmployeeById(int $id): ?PayrollEmployee
    {
        return $this->payrollEmployeeRepository->withTimesheets()->find($id);
    }

    public function createEmployee(array $data): PayrollEmployee
    {
        return $this->payrollEmployeeRepository->create($data);
    }

    public function updateEmployee(int $id, array $data): PayrollEmployee
    {
        return $this->payrollEmployeeRepository->update($id, $data);
    }

    public function deleteEmployee(int $id): bool
    {
        return $this->payrollEmployeeRepository->delete($id);
    }

    public function recalculateTotals(int $id): PayrollEmployee
    {
        $employee = $this->payrollEmployeeRepository->findOrFail($id);
        $employee->calculateTotals();
        return $employee->fresh();
    }
}
