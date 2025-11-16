<?php

namespace App\Services;

use App\Contracts\Repositories\WorkAssignmentRepositoryInterface;
use App\Models\WorkAssignment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class WorkAssignmentService extends BaseService
{
    public function __construct(
        protected WorkAssignmentRepositoryInterface $workAssignmentRepository
    ) {}

    public function getPaginatedWorkAssignments(int $perPage = 15): LengthAwarePaginator
    {
        return $this->workAssignmentRepository->withEmployees()->paginate($perPage);
    }

    public function getWorkAssignmentById(int $id): ?WorkAssignment
    {
        return $this->workAssignmentRepository->withEmployees()->find($id);
    }

    public function createWorkAssignment(array $data): WorkAssignment
    {
        return $this->executeInTransaction(function () use ($data) {
            $assignmentData = array_diff_key($data, array_flip(['employees']));
            $workAssignment = $this->workAssignmentRepository->create($assignmentData);

            if (isset($data['employees'])) {
                foreach ($data['employees'] as $employee) {
                    $workAssignment->employees()->attach(
                        $employee['employee_id'],
                        ['detail' => $employee['detail'] ?? null]
                    );
                }
            }

            return $workAssignment->load('employees');
        });
    }

    public function updateWorkAssignment(int $id, array $data): WorkAssignment
    {
        return $this->executeInTransaction(function () use ($id, $data) {
            $assignmentData = array_diff_key($data, array_flip(['employees']));
            $workAssignment = $this->workAssignmentRepository->update($id, $assignmentData);

            if (isset($data['employees'])) {
                $workAssignment->employees()->detach();
                foreach ($data['employees'] as $employee) {
                    $workAssignment->employees()->attach(
                        $employee['employee_id'],
                        ['detail' => $employee['detail'] ?? null]
                    );
                }
            }

            return $workAssignment->load('employees');
        });
    }

    public function deleteWorkAssignment(int $id): bool
    {
        return $this->workAssignmentRepository->delete($id);
    }
}
