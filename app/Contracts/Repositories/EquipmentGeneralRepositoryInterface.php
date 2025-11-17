<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface EquipmentGeneralRepositoryInterface extends RepositoryInterface
{
    /**
     * Get equipment by type
     */
    public function byType(string $type): Collection;

    /**
     * Get equipment by condition
     */
    public function byCondition(string $condition): Collection;
}
