<?php

namespace App\Contracts\Repositories;

interface ScheduleItemRepositoryInterface extends RepositoryInterface
{
    /**
     * Delete items by schedule ID
     */
    public function deleteByScheduleId(int $scheduleId): bool;
}
