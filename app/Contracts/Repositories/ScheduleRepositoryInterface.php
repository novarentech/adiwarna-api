<?php

namespace App\Contracts\Repositories;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Collection;

interface ScheduleRepositoryInterface extends RepositoryInterface
{
    /**
     * Get schedules with work orders and customer
     */
    public function withRelations(): self;

    /**
     * Get schedules with customer only (for list view)
     */
    public function withCustomerOnly(): self;

    /**
     * Search schedules by customer name or PIC name
     */
    public function search(string $keyword): self;

    /**
     * Get schedules by customer
     */
    public function byCustomer(int $customerId): Collection;

    /**
     * Get schedules by date range
     */
    public function byDateRange(string $startDate, string $endDate): Collection;
}
