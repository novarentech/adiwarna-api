<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkOrderListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'work_order_no' => $this->work_order_no,
            'work_order_year' => $this->work_order_year,
            'date' => $this->date->format('Y-m-d'),
            'employees' => $this->employees->pluck('name')->toArray(),
            'scope_of_work' => $this->scope_of_work,
            'customer' => $this->customer?->name,
            'work_location' => $this->customerLocation?->location_name,
        ];
    }
}
