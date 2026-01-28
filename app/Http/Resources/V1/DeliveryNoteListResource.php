<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryNoteListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'dn_no' => $this->dn_no,
            'dn_date' => $this->dn_date,
            'date' => $this->date->format('d/m/Y'),
            'customer' => $this->customer->name ?? $this->other['name'],
            'wo_no' => $this->wo_no,
            'vehicle_plate' => $this->vehicle_plate,
            'total_items' => $this->items_count ?? 0,
            'status' => $this->status?->label() ?? 'Pending',
        ];
    }
}
