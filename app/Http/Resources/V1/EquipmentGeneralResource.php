<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EquipmentGeneralResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'merk_type' => $this->merk_type,
            'serial_number' => $this->serial_number,
            'duration' => $this->duration_months->value . ' Months',
            'calibration_date' => $this->calibration_date->format('Y-m-d'),
            'expired_date' => $this->expired_date->format('Y-m-d'),
            'calibration_agency' => $this->calibration_agency->value,
            'condition' => $this->condition->value,
        ];
    }
}
