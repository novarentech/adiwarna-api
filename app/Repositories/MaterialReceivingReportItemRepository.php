<?php

namespace App\Repositories;

use App\Contracts\Repositories\MaterialReceivingReportItemRepositoryInterface;
use App\Models\MaterialReceivingReportItem;

class MaterialReceivingReportItemRepository extends BaseRepository implements MaterialReceivingReportItemRepositoryInterface
{
    protected function model(): string
    {
        return MaterialReceivingReportItem::class;
    }

    public function deleteByMaterialReceivingReportId(int $mrrId): bool
    {
        return $this->model->where('material_receiving_report_id', $mrrId)->delete();
    }
}
