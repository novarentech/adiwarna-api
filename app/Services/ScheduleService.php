<?php

namespace App\Services;

use App\Contracts\Repositories\ScheduleRepositoryInterface;
use App\Contracts\Repositories\ScheduleItemRepositoryInterface;
use App\Models\Schedule;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ScheduleService extends BaseService
{
    public function __construct(
        protected ScheduleRepositoryInterface $scheduleRepository,
        protected ScheduleItemRepositoryInterface $scheduleItemRepository
    ) {}

    public function getPaginatedSchedules(int $perPage = 15): LengthAwarePaginator
    {
        return $this->scheduleRepository->withRelations()->paginate($perPage);
    }

    public function getScheduleById(int $id): ?Schedule
    {
        return $this->scheduleRepository->withRelations()->find($id);
    }

    public function createScheduleWithItems(array $data): Schedule
    {
        return $this->executeInTransaction(function () use ($data) {
            $scheduleData = array_diff_key($data, array_flip(['items']));
            $schedule = $this->scheduleRepository->create($scheduleData);

            if (isset($data['items'])) {
                foreach ($data['items'] as $item) {
                    $schedule->items()->create($item);
                }
            }

            return $schedule->load('items', 'customer');
        });
    }

    public function updateScheduleWithItems(int $id, array $data): Schedule
    {
        return $this->executeInTransaction(function () use ($id, $data) {
            $scheduleData = array_diff_key($data, array_flip(['items']));
            $schedule = $this->scheduleRepository->update($id, $scheduleData);

            if (isset($data['items'])) {
                $schedule->items()->delete();
                foreach ($data['items'] as $item) {
                    $schedule->items()->create($item);
                }
            }

            return $schedule->load('items', 'customer');
        });
    }

    public function deleteSchedule(int $id): bool
    {
        return $this->scheduleRepository->delete($id);
    }
}
