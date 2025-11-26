<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayrollTimesheetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date->format('Y-m-d'),
            'attendance_status' => $this->attendance_status->value,
            'regular_hours' => $this->regular_hours,
            'overtime_hours' => $this->overtime_hours,
            'total_allowances' => $this->total_allowances,
            'total_overtime_pay' => $this->total_overtime_pay,
            'late_minutes' => $this->late_minutes,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
