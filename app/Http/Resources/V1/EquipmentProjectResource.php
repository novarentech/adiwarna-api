<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EquipmentProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'project_date' => $this->project_date->format('Y-m-d'),
            'customer' => $this->customer?->name,
            'location' => $this->customerLocation?->location_name,
            'prepared_by' => $this->prepared_by,
            'verified_by' => $this->verified_by,
            'equipments' => $this->whenLoaded('equipments', function () {
                return $this->equipments->map(function ($equipment) {
                    return [
                        'id' => $equipment->id,
                        'description' => $equipment->description,
                        'merk_type' => $equipment->merk_type,
                        'serial_number' => $equipment->serial_number,
                        'calibration_date' => $equipment->calibration_date->format('Y-m-d'),
                    ];
                });
            }),
        ];
    }
}
