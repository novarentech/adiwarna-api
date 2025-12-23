<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaterialReceivingReportItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'order_qty' => $this->order_qty,
            'received_qty' => $this->received_qty,
            'remarks' => $this->remarks,
        ];
    }
}
