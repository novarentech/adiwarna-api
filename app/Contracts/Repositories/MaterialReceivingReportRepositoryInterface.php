<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface MaterialReceivingReportRepositoryInterface extends RepositoryInterface
{
    /**
     * Get MRRs with items
     */
    public function withItems(): self;

    /**
     * Get MRRs with items count
     */
    public function withItemsCount(): self;

    /**
     * Search MRRs
     */
    public function search(?string $search = null): self;

    /**
     * Get MRRs by date range
     */
    public function byDateRange(string $startDate, string $endDate): Collection;
}
