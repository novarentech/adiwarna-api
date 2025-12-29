<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayrollPeriodListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'payroll_project_id' => $this->payroll_project_id,
            'month' => $this->month,
            'year' => $this->year,
            'period_start' => $this->period_start->format('d-m-Y'),
            'period_end' => $this->period_end->format('d-m-Y'),
            'status' => $this->status,
            'employees_count' => $this->employees_count ?? 0,
        ];
    }
}
