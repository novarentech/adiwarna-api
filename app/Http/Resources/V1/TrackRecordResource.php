<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrackRecordResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'project_name' => $this->project_name,
            'customer_id' => $this->customer_id,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'date' => $this->date,
            'status' => $this->status,
            'description' => $this->description,
            'milestones' => $this->milestones,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
