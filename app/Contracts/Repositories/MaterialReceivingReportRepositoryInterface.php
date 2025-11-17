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
     * Get MRRs by date range
     */
    public function byDateRange(string $startDate, string $endDate): Collection;
}
