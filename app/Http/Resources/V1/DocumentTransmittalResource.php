<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentTransmittalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'customer_name' => $this->customer_name,
            'customer_address' => $this->customer_address,
            'pic_name' => $this->pic_name,
            'pic_phone' => $this->pic_phone,
            'date' => $this->date,
            'description' => $this->description,
            'documents' => TransmittalDocumentResource::collection($this->whenLoaded('documents')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
