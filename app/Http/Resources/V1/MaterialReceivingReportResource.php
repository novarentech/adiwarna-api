<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaterialReceivingReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'po_no' => $this->po_no,
            'po_year' => $this->po_year,
            'supplier' => $this->supplier,
            'receiving_date' => $this->receiving_date->format('Y-m-d'),
            'order_by' => $this->order_by?->value,
            'received_by' => $this->received_by,
            'received_position' => $this->received_position,
            'acknowledge_by' => $this->acknowledge_by,
            'acknowledge_position' => $this->acknowledge_position,
            'status' => $this->status?->value,
            'items' => MaterialReceivingReportItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
