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
     * Search PRs by PR number, supplier, or place of delivery
     */
    public function search(?string $search = null): self;

    /**
     * Get PRs by date range
     */
    public function byDateRange(string $startDate, string $endDate): Collection;
}
