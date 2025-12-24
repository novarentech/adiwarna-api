<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseRequisitionItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'qty' => $this->qty,
            'unit' => $this->unit,
            'description' => $this->description,
            'unit_price' => $this->unit_price,
            'total_price' => $this->total_price,
        ];
    }
}
