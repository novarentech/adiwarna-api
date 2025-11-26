<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DailyActivityListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'po_no' => $this->po_no,
            'po_year' => $this->po_year,
            'ref_no' => $this->ref_no,
            'customer' => $this->customer ? $this->customer->name : null,
            'location' => $this->location,
            'date' => $this->date->format('Y-m-d'),
            'prepared_name' => $this->prepared_name,
        ];
    }
}
