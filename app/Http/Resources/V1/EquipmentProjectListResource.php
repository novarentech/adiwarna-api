<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EquipmentProjectListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'customer_location_id' => $this->customer_location_id,
            'project_date' => $this->project_date->format('d/m/Y'),
            'customer' => $this->customer?->name,
            'location' => $this->customerLocation?->location_name,
            'prepared_by' => $this->prepared_by,
            'verified_by' => $this->verified_by,
        ];
    }
}
