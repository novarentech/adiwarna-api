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
            'period_start' => $this->period_start->format('Y-m-d'),
            'period_end' => $this->period_end->format('Y-m-d'),
            'status' => $this->status,
            'enable_progressive_ot' => $this->enable_progressive_ot,
            'enable_bpjs' => $this->enable_bpjs,
            'enable_pph21' => $this->enable_pph21,
            'enable_meal_allowance' => $this->enable_meal_allowance,
            'enable_perjadin' => $this->enable_perjadin,
            'enable_driver_rules' => $this->enable_driver_rules,
            'bpjs_jht_rate' => $this->bpjs_jht_rate,
            'bpjs_pensiun_rate' => $this->bpjs_pensiun_rate,
            'bpjs_kesehatan_rate' => $this->bpjs_kesehatan_rate,
            'bpjs_max_salary_jht' => $this->bpjs_max_salary_jht,
            'bpjs_max_salary_kesehatan' => $this->bpjs_max_salary_kesehatan,
            'meal_allowance_rate' => $this->meal_allowance_rate,
            'perjadin_breakfast_rate' => $this->perjadin_breakfast_rate,
            'perjadin_lunch_rate' => $this->perjadin_lunch_rate,
            'perjadin_dinner_rate' => $this->perjadin_dinner_rate,
            'perjadin_daily_rate' => $this->perjadin_daily_rate,
            'perjadin_accommodation_rate' => $this->perjadin_accommodation_rate,
            'driver_max_payroll_ot' => $this->driver_max_payroll_ot,
            'employees' => PayrollEmployeeResource::collection($this->whenLoaded('employees')),
        ];
    }
}
