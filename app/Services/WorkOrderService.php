<?php

namespace App\Services;

use App\Contracts\Repositories\WorkOrderRepositoryInterface;
use App\Models\WorkOrder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class WorkOrderService extends BaseService
{
    public function __construct(
        protected WorkOrderRepositoryInterface $workOrderRepository
    ) {}

    public function getPaginatedWorkOrders(int $perPage = 15, ?string $search = null, string $sortOrder = 'desc'): LengthAwarePaginator
    {
        $query = $this->workOrderRepository->withCustomerAndLocation();

        if ($search) {
            $query->search($search);
        }

        $query->sortBy($sortOrder);

        return $query->paginate($perPage);
    }

    public function getWorkOrderById(int $id): ?WorkOrder
    {
        return $this->workOrderRepository->withRelations()->find($id);
    }

    public function createWorkOrderWithEmployees(array $data): WorkOrder
    {
        return $this->executeInTransaction(function () use ($data) {
            $workOrderData = array_diff_key($data, array_flip(['employees']));
            $workOrder = $this->workOrderRepository->create($workOrderData);

            if (isset($data['employees'])) {
                $employeeIds = collect($data['employees'])->pluck('employee_id')->toArray();
                $workOrder->employees()->attach($employeeIds);
            }

            return $workOrder->load('employees', 'customer', 'customerLocation');
        });
    }

    public function updateWorkOrderWithEmployees(int $id, array $data): WorkOrder
    {
        return $this->executeInTransaction(function () use ($id, $data) {
            $workOrderData = array_diff_key($data, array_flip(['employees']));
            $workOrder = $this->workOrderRepository->update($id, $workOrderData);

            if (isset($data['employees'])) {
                // Get existing employee pivot IDs from request
                $requestEmployeeIds = collect($data['employees'])
                    ->pluck('id')
                    ->filter()
                    ->toArray();

                // Delete employees that are not in the request
                $workOrder->employees()
                    ->wherePivotNotIn('id', $requestEmployeeIds)
                    ->detach();

                // Update or create employees
                foreach ($data['employees'] as $employeeData) {
                    if (isset($employeeData['id'])) {
                        // Employee already exists, no need to update pivot (no additional data)
                        continue;
                    } else {
                        // Create new employee
                        $workOrder->employees()->attach($employeeData['employee_id']);
                    }
                }
            }

            return $workOrder->load('employees', 'customer', 'customerLocation');
        });
    }

    public function deleteWorkOrder(int $id): bool
    {
        return $this->workOrderRepository->delete($id);
    }
}
