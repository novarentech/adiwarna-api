<?php

namespace App\Services;

use App\Contracts\Repositories\PayrollPeriodRepositoryInterface;
use App\Models\PayrollPeriod;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PayrollPeriodService extends BaseService
{
    public function __construct(
        protected PayrollPeriodRepositoryInterface $payrollPeriodRepository
    ) {}

    public function getPaginatedPeriods(int $perPage = 15): LengthAwarePaginator
    {
        return $this->payrollPeriodRepository->paginate($perPage);
    }

    public function getPeriodById(int $id): ?PayrollPeriod
    {
        return $this->payrollPeriodRepository->withEmployees()->find($id);
    }

    public function createPeriod(array $data): PayrollPeriod
    {
        return $this->payrollPeriodRepository->create($data);
    }

    public function updatePeriod(int $id, array $data): PayrollPeriod
    {
        return $this->payrollPeriodRepository->update($id, $data);
    }

    public function deletePeriod(int $id): bool
    {
        return $this->payrollPeriodRepository->delete($id);
    }
}
