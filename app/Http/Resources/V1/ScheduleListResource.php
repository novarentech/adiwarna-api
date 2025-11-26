<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ta_no' => $this->ta_no,
            'date' => $this->date->format('Y-m-d'),
            'customer' => $this->customer?->name,
            'pic' => $this->pic_name,
        ];
    }
}
