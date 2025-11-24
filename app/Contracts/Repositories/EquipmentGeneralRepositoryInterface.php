<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface EquipmentGeneralRepositoryInterface extends RepositoryInterface
{
    /**
     * Search equipment by description, merk/type, serial number, agency, or condition
     */
    public function search(string $keyword): self;

    /**
     * Get equipment by condition
     */
    public function byCondition(string $condition): Collection;

    /**
     * Get equipment by calibration agency
     */
    public function byCalibrationAgency(string $agency): Collection;
}
