<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface WorkOrderRepositoryInterface extends RepositoryInterface
{
    /**
     * Get work orders with employees and customer
     */
    public function withRelations(): self;

    /**
     * Get work orders with customer and location only (for list view)
     */
    public function withCustomerAndLocation(): self;

    /**
     * Search work orders by workers, scope of work, customer, or work location
     */
    public function search(string $keyword): self;

    /**
     * Get work orders by customer
     */
    public function byCustomer(int $customerId): Collection;

    /**
     * Get work orders by status
     */
    public function byStatus(string $status): Collection;

    /**
     * Filter work orders by date range
     */
    public function byDateRange(string $startDate, string $endDate): self;
}
