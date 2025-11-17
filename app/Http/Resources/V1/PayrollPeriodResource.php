<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayrollPeriodResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'payroll_project_id' => $this->payroll_project_id,
            'month' => $this->month,
            'year' => $this->year,
            'name' => $this->name,
            'period_start' => $this->period_start,
            'period_end' => $this->period_end,
            'status' => $this->status,
            'enable_progressive_ot' => $this->enable_progressive_ot,
            'enable_bpjs' => $this->enable_bpjs,
            'enable_pph21' => $this->enable_pph21,
            'bpjs_jht_rate' => $this->bpjs_jht_rate,
            'bpjs_pensiun_rate' => $this->bpjs_pensiun_rate,
            'bpjs_kesehatan_rate' => $this->bpjs_kesehatan_rate,
            'employees' => PayrollEmployeeResource::collection($this->whenLoaded('employees')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
