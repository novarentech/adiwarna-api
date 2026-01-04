<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseRequisitionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'pr_no' => $this->pr_no,
            'date' => $this->date->format('Y-m-d'),
            'po_no_cash' => $this->po_no_cash,
            'supplier' => $this->supplier?->value,
            'routing' => $this->routing?->value,
            'sub_total' => $this->sub_total,
            'vat_percentage' => $this->vat_percentage,
            'vat_amount' => $this->vat_amount,
            'total_amount' => $this->total_amount,
            'requested_by' => $this->requested_by,
            'requested_position' => $this->requested_position,
            'approved_by' => $this->approved_by,
            'approved_position' => $this->approved_position,
            'authorized_by' => $this->authorized_by,
            'status' => $this->status?->value,
            'items' => PurchaseRequisitionItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
