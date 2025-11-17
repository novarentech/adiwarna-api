<?php

namespace App\Contracts\Repositories;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Collection;

interface ScheduleRepositoryInterface extends RepositoryInterface
{
    /**
     * Get schedules with items and customer
     */
    public function withRelations(): self;

    /**
     * Get schedules by customer
     */
    public function byCustomer(int $customerId): Collection;

    /**
     * Get schedules by date range
     */
    public function byDateRange(string $startDate, string $endDate): Collection;
}
