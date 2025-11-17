<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface TrackRecordRepositoryInterface extends RepositoryInterface
{
    /**
     * Get track records with customer
     */
    public function withCustomer(): self;

    /**
     * Get track records by customer
     */
    public function byCustomer(int $customerId): Collection;

    /**
     * Get track records by date range
     */
    public function byDateRange(string $startDate, string $endDate): Collection;

    /**
     * Get track records by status
     */
    public function byStatus(string $status): Collection;
}
