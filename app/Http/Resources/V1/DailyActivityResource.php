<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DailyActivityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'po_no' => $this->po_no,
            'po_year' => $this->po_year,
            'ref_no' => $this->ref_no,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'date' => $this->date,
            'location' => $this->location,
            'time_from' => $this->time_from,
            'time_to' => $this->time_to,
            'prepared_name' => $this->prepared_name,
            'prepared_pos' => $this->prepared_pos,
            'acknowledge_name' => $this->acknowledge_name,
            'acknowledge_pos' => $this->acknowledge_pos,
            'members' => $this->whenLoaded('members'),
            'descriptions' => $this->whenLoaded('descriptions'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
