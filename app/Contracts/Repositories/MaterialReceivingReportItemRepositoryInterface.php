<?php

namespace App\Contracts\Repositories;

interface MaterialReceivingReportItemRepositoryInterface extends RepositoryInterface
{
    /**
     * Delete items by material receiving report ID
     */
    public function deleteByMaterialReceivingReportId(int $mrrId): bool;
}
