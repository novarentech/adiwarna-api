<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrderListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'po_no' => $this->po_no,
            'po_year' => $this->po_year,
            'date' => $this->date->format('Y-m-d'),
            'customer' => $this->customer ? $this->customer->name : null,
            'pic_name' => $this->pic_name,
            'pic_phone' => $this->pic_phone,
            'required_date' => $this->required_date,
        ];
    }
}
