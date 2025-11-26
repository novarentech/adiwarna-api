<?php

namespace App\Services;

use App\Contracts\Repositories\ScheduleRepositoryInterface;
use App\Models\Schedule;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ScheduleService extends BaseService
{
    public function __construct(
        protected ScheduleRepositoryInterface $scheduleRepository
    ) {}

    public function getPaginatedSchedules(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->scheduleRepository->withCustomerOnly();

        if ($search) {
            $query->search($search);
        }

        return $query->paginate($perPage);
    }

    public function getScheduleById(int $id): ?Schedule
    {
        return $this->scheduleRepository->withRelations()->find($id);
    }

    public function createScheduleWithWorkOrders(array $data): Schedule
    {
        return $this->executeInTransaction(function () use ($data) {
            $scheduleData = array_diff_key($data, array_flip(['work_orders']));
            $schedule = $this->scheduleRepository->create($scheduleData);

            if (isset($data['work_orders'])) {
                foreach ($data['work_orders'] as $workOrder) {
                    $schedule->workOrders()->create($workOrder);
                }
            }

            return $schedule->load('workOrders', 'customer');
        });
    }

    public function updateScheduleWithWorkOrders(int $id, array $data): Schedule
    {
        return $this->executeInTransaction(function () use ($id, $data) {
            $scheduleData = array_diff_key($data, array_flip(['work_orders']));

            // Only update schedule fields if there are any
            if (!empty($scheduleData)) {
                $schedule = $this->scheduleRepository->update($id, $scheduleData);
            } else {
                $schedule = $this->scheduleRepository->find($id);
            }

            if (isset($data['work_orders'])) {
                // Get existing work order IDs from request
                $requestWorkOrderIds = collect($data['work_orders'])
                    ->pluck('id')
                    ->filter()
                    ->toArray();

                // Delete work orders that are not in the request
                $schedule->workOrders()
                    ->whereNotIn('id', $requestWorkOrderIds)
                    ->delete();

                // Update or create work orders
                foreach ($data['work_orders'] as $workOrderData) {
                    if (isset($workOrderData['id'])) {
                        // Update existing work order
                        $schedule->workOrders()
                            ->where('id', $workOrderData['id'])
                            ->update(array_diff_key($workOrderData, array_flip(['id'])));
                    } else {
                        // Create new work order
                        $schedule->workOrders()->create($workOrderData);
                    }
                }
            }

            return $schedule->load('workOrders', 'customer');
        });
    }

    public function deleteSchedule(int $id): bool
    {
        return $this->scheduleRepository->delete($id);
    }
}
