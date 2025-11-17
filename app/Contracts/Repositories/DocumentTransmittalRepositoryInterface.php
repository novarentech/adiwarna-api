<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface DocumentTransmittalRepositoryInterface extends RepositoryInterface
{
    /**
     * Get transmittals with documents and customer
     */
    public function withRelations(): self;

    /**
     * Get transmittals by customer
     */
    public function byCustomer(int $customerId): Collection;

    /**
     * Get transmittals by date range
     */
    public function byDateRange(string $startDate, string $endDate): Collection;
}
