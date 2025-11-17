<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface OperationalRepositoryInterface extends RepositoryInterface
{
    /**
     * Get operational data by date range
     */
    public function byDateRange(string $startDate, string $endDate): Collection;

    /**
     * Get operational data by type
     */
    public function byType(string $type): Collection;
}
