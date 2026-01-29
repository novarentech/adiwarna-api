<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuotationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date->format('Y-m-d'),
            'ref_no' => $this->ref_no,
            'ref_year' => $this->ref_year,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'pic_name' => $this->pic_name,
            'pic_phone' => $this->pic_phone,
            'subject' => $this->subject,
            'top' => $this->top,
            'valid_until' => $this->valid_until,
            'clause' => $this->clause,
            'workday' => $this->workday,
            'auth_name' => $this->auth_name,
            'auth_position' => $this->auth_position,
            'discount' => $this->discount,
            'items' => QuotationItemResource::collection($this->whenLoaded('items')),
            'adiwarnas' => $this->whenLoaded('adiwarnas'),
            'clients' => $this->whenLoaded('clients'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
