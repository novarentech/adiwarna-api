<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface EquipmentProjectRepositoryInterface extends RepositoryInterface
{
    /**
     * Get equipment projects with all relations
     */
    public function withRelations(): self;

    /**
     * Get equipment projects with customer and location only
     */
    public function withCustomerAndLocation(): self;

    /**
     * Search equipment projects by customer, location, prepared by, or verified by
     */
    public function search(string $keyword): self;

    /**
     * Get equipment projects by customer
     */
    public function byCustomer(int $customerId): Collection;
}
