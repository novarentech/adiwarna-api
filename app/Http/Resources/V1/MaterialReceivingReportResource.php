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
            'po_inv_pr_no' => $this->po_inv_pr_no,
            'supplier' => $this->supplier,
            'receiving_date' => $this->receiving_date->format('Y-m-d'),
            'order_by' => $this->order_by?->value,
            'received_by' => $this->received_by,
            'acknowledge_by' => $this->acknowledge_by,
            'status' => $this->status?->value,
            'notes' => $this->notes,
            'items' => MaterialReceivingReportItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
