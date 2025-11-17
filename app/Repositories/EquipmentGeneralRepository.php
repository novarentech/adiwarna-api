<?php

namespace App\Repositories;

use App\Contracts\Repositories\EquipmentGeneralRepositoryInterface;
use App\Models\EquipmentGeneral;
use Illuminate\Database\Eloquent\Collection;

class EquipmentGeneralRepository extends BaseRepository implements EquipmentGeneralRepositoryInterface
{
    protected function model(): string
    {
        return EquipmentGeneral::class;
    }

    public function byType(string $type): Collection
    {
        return $this->model->where('equipment_type', $type)->get();
    }

    public function byCondition(string $condition): Collection
    {
        return $this->model->where('condition', $condition)->get();
    }
}
