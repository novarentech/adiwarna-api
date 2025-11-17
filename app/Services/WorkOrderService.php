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

    public function getPaginatedWorkOrders(int $perPage = 15): LengthAwarePaginator
    {
        return $this->workOrderRepository->withRelations()->paginate($perPage);
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
                $employees = [];
                foreach ($data['employees'] as $employee) {
                    $employees[$employee['employee_id']] = [
                        'detail' => $employee['detail'] ?? null
                    ];
                }
                $workOrder->employees()->attach($employees);
            }

            return $workOrder->load('employees', 'customer');
        });
    }

    public function updateWorkOrderWithEmployees(int $id, array $data): WorkOrder
    {
        return $this->executeInTransaction(function () use ($id, $data) {
            $workOrderData = array_diff_key($data, array_flip(['employees']));
            $workOrder = $this->workOrderRepository->update($id, $workOrderData);

            if (isset($data['employees'])) {
                $employees = [];
                foreach ($data['employees'] as $employee) {
                    $employees[$employee['employee_id']] = [
                        'detail' => $employee['detail'] ?? null
                    ];
                }
                $workOrder->employees()->sync($employees);
            }

            return $workOrder->load('employees', 'customer');
        });
    }

    public function deleteWorkOrder(int $id): bool
    {
        return $this->workOrderRepository->delete($id);
    }
}
