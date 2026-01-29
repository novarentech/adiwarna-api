<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_no' => $this->customer_no,
            'name' => $this->name,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            'customer_locations' => $this->locations->map(function ($location) {
                return [
                    'id' => $location->id,
                    'location_name' => $location->location_name,
                ];
            }),
        ];
    }
}
