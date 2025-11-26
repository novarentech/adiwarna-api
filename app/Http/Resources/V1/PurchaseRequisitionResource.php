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
            'pr_year' => $this->pr_year,
            'date' => $this->date->format('Y-m-d'),
            'supplier' => $this->supplier,
            'delivery_place' => $this->delivery_place,
            'discount' => $this->discount,
            'notes' => $this->notes,
            'total_amount' => $this->total_amount,
            'items' => PurchaseRequisitionItemResource::collection($this->whenLoaded('items')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
