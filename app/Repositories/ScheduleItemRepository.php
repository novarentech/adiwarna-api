<?php

namespace App\Repositories;

use App\Contracts\Repositories\ScheduleItemRepositoryInterface;
use App\Models\ScheduleItem;

class ScheduleItemRepository extends BaseRepository implements ScheduleItemRepositoryInterface
{
    protected function model(): string
    {
        return ScheduleItem::class;
    }

    public function deleteByScheduleId(int $scheduleId): bool
    {
        return $this->model->where('schedule_id', $scheduleId)->delete();
    }
}
