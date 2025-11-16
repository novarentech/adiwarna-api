<?php

namespace App\Services;

use App\Contracts\Repositories\PayrollProjectRepositoryInterface;
use App\Models\PayrollProject;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PayrollProjectService extends BaseService
{
    public function __construct(
        protected PayrollProjectRepositoryInterface $payrollProjectRepository
    ) {}

    public function getPaginatedProjects(int $perPage = 15): LengthAwarePaginator
    {
        return $this->payrollProjectRepository->paginate($perPage);
    }

    public function getProjectById(int $id): ?PayrollProject
    {
        return $this->payrollProjectRepository->withPeriods()->find($id);
    }

    public function createProject(array $data): PayrollProject
    {
        return $this->payrollProjectRepository->create($data);
    }

    public function updateProject(int $id, array $data): PayrollProject
    {
        return $this->payrollProjectRepository->update($id, $data);
    }

    public function deleteProject(int $id): bool
    {
        return $this->payrollProjectRepository->delete($id);
    }
}
