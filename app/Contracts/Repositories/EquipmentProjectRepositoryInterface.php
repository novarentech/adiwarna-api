<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface EquipmentProjectRepositoryInterface extends RepositoryInterface
{
    /**
     * Get equipment projects with customer
     */
    public function withCustomer(): self;

    /**
     * Get equipment projects by customer
     */
    public function byCustomer(int $customerId): Collection;

    /**
     * Get equipment projects by project name
     */
    public function byProjectName(string $projectName): Collection;
}
