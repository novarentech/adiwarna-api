<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayrollEmployeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_name' => $this->employee_name,
            'employee_no' => $this->employee_no,
            'position' => $this->position,
            'employee_category' => $this->employee_category->value,
            'base_salary' => $this->base_salary,
            'working_hours' => $this->working_hours,
            'total_working_days' => $this->total_working_days,
            'total_present_days' => $this->total_present_days,
            'total_overtime_hours' => $this->total_overtime_hours,
            'total_allowances' => $this->total_allowances,
            'bpjs_jht_deduction' => $this->bpjs_jht_deduction,
            'bpjs_pensiun_deduction' => $this->bpjs_pensiun_deduction,
            'bpjs_kesehatan_deduction' => $this->bpjs_kesehatan_deduction,
            'pph21_amount' => $this->pph21_amount,
            'gross_salary' => $this->gross_salary,
            'total_deductions' => $this->total_deductions,
            'net_salary' => $this->net_salary,
            'hourly_rate' => $this->hourly_rate,
            'timesheets' => PayrollTimesheetResource::collection($this->whenLoaded('timesheets')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
