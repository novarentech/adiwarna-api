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
            'rev_no' => $this->rev_no,
            'date' => $this->date->format('Y-m-d'),
            'required_delivery' => $this->required_delivery->format('Y-m-d'),
            'po_no_cash' => $this->po_no_cash,
            'supplier' => $this->supplier,
            'place_of_delivery' => $this->place_of_delivery,
            'routing' => $this->routing?->value,
            'sub_total' => $this->sub_total,
            'vat_percentage' => $this->vat_percentage,
            'vat_amount' => $this->vat_amount,
            'total_amount' => $this->total_amount,
            'requested_by' => $this->requested_by,
            'approved_by' => $this->approved_by,
            'authorized_by' => $this->authorized_by,
            'status' => $this->status?->value,
            'notes' => $this->notes,
            'items' => PurchaseRequisitionItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
