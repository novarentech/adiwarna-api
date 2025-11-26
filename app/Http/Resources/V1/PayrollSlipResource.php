<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayrollSlipResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'payroll_period_id' => $this->payroll_period_id,
            'payroll_employee_id' => $this->payroll_employee_id,
            'slip_type' => $this->slip_type,
            'generated_at' => $this->generated_at?->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),

            // Relationships
            'period' => new PayrollPeriodResource($this->whenLoaded('period')),
            'employee' => new PayrollEmployeeResource($this->whenLoaded('employee')),
        ];
    }
}
