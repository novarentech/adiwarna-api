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
            'equipment_name' => $this->equipment_name,
            'equipment_type' => $this->equipment_type,
            'quantity' => $this->quantity,
            'condition' => $this->condition,
            'specifications' => $this->specifications,
            'purchase_date' => $this->purchase_date,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
