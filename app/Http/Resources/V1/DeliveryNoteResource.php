<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryNoteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'delivery_note_no' => $this->delivery_note_no,
            'date' => $this->date->format('Y-m-d'),
            'customer' => $this->customer,
            'customer_address' => $this->customer_address,
            'wo_no' => $this->wo_no,
            'delivered_with' => $this->delivered_with,
            'vehicle_plate' => $this->vehicle_plate,
            'delivered_by' => $this->delivered_by,
            'received_by' => $this->received_by,
            'status' => $this->status?->value,
            'notes' => $this->notes,
            'items' => DeliveryNoteItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
