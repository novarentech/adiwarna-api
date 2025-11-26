<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkAssignmentListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'assignment_no' => $this->assignment_no,
            'assignment_year' => $this->assignment_year,
            'ref_no' => $this->ref_no,
            'ref_year' => $this->ref_year,
            'date' => $this->date->format('Y-m-d'),
            'customer' => $this->customer ? $this->customer->name : null,
            'work_location' => $this->customerLocation ? $this->customerLocation->location_name : null,
        ];
    }
}
