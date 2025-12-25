<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface DeliveryNoteRepositoryInterface extends RepositoryInterface
{
    /**
     * Get delivery notes with items
     */
    public function withItems(): self;

    /**
     * Get delivery notes with items count
     */
    public function withItemsCount(): self;

    /**
     * Search delivery notes by delivery note number, customer, WO number, or vehicle plate
     */
    public function search(?string $search = null): self;

    /**
     * Get delivery notes by date range
     */
    public function byDateRange(string $startDate, string $endDate): Collection;
}
