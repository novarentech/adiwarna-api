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

    public function getPaginatedWorkAssignments(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->workAssignmentRepository->withCustomerOnly();

        if ($search) {
            $query->search($search);
        }

        return $query->paginate($perPage);
    }

    public function getWorkAssignmentById(int $id): ?WorkAssignment
    {
        return $this->workAssignmentRepository->withWorkers()->find($id);
    }

    public function createWorkAssignment(array $data): WorkAssignment
    {
        return $this->executeInTransaction(function () use ($data) {
            $assignmentData = array_diff_key($data, array_flip(['workers']));
            $workAssignment = $this->workAssignmentRepository->create($assignmentData);

            if (isset($data['workers'])) {
                foreach ($data['workers'] as $worker) {
                    $workAssignment->workers()->create($worker);
                }
            }

            return $workAssignment->load('workers');
        });
    }

    public function updateWorkAssignment(int $id, array $data): WorkAssignment
    {
        return $this->executeInTransaction(function () use ($id, $data) {
            $assignmentData = array_diff_key($data, array_flip(['workers']));
            $workAssignment = $this->workAssignmentRepository->update($id, $assignmentData);

            if (isset($data['workers'])) {
                $existingWorkerIds = [];

                foreach ($data['workers'] as $workerData) {
                    if (isset($workerData['id']) && $workerData['id']) {
                        // Update existing worker
                        $worker = $workAssignment->workers()->find($workerData['id']);
                        if ($worker) {
                            $worker->update($workerData);
                            $existingWorkerIds[] = $workerData['id'];
                        }
                    } else {
                        // Create new worker
                        $newWorker = $workAssignment->workers()->create($workerData);
                        $existingWorkerIds[] = $newWorker->id;
                    }
                }

                // Delete workers that are not in the request
                $workAssignment->workers()->whereNotIn('id', $existingWorkerIds)->delete();
            }

            return $workAssignment->load('workers');
        });
    }

    public function deleteWorkAssignment(int $id): bool
    {
        return $this->workAssignmentRepository->delete($id);
    }
}
