<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayrollProjectListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'company_name' => $this->company_name,
            'start_date' => $this->start_date->format('d-m-Y'),
            'end_date' => $this->end_date?->format('d-m-Y'),
            'status' => $this->status->value,
            'payroll_period_id' => $this->periods->first()?->id,
        ];
    }
}
