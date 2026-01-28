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
            'dn_no' => $this->dn_no,
            'dn_date' => $this->dn_date,
            'date' => $this->date->format('Y-m-d'),
            'isOther' => $this->isOther,
            'customer_id' => $this->customer_id,
            'customer' => $this->customer->name
                ? $this->whenLoaded('customer', function () {
                    return [
                        'id' => $this->customer->id,
                        'name' => $this->customer->name,
                        'customer_no' => $this->customer->customer_no,
                        'address' => $this->customer->address,
                    ];
                })
                : [
                    'id' => null,
                    'name' => $this->other['name'] ?? 'Other',
                    'customer_no' => $this->other['phone'] ?? '',
                    'address' => $this->other['address'] ?? '',
                ],
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
