<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_no' => $this->customer_no,
            'name' => $this->name,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            'locations' => $this->whenLoaded('locations'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
