<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface PurchaseRequisitionRepositoryInterface extends RepositoryInterface
{
    /**
     * Get PRs with items
     */
    public function withItems(): self;

    /**
     * Get PRs by date range
     */
    public function byDateRange(string $startDate, string $endDate): Collection;
}
