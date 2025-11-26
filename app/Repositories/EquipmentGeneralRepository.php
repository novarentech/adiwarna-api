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

    public function search(string $keyword): self
    {
        $this->query->where(function ($query) use ($keyword) {
            $query->where('description', 'like', "%{$keyword}%")
                ->orWhere('merk_type', 'like', "%{$keyword}%")
                ->orWhere('serial_number', 'like', "%{$keyword}%")
                ->orWhere('calibration_agency', 'like', "%{$keyword}%")
                ->orWhere('condition', 'like', "%{$keyword}%");
        });
        return $this;
    }

    public function byCondition(string $condition): Collection
    {
        return $this->model->where('condition', $condition)->get();
    }

    public function byCalibrationAgency(string $agency): Collection
    {
        return $this->model->where('calibration_agency', $agency)->get();
    }
}
