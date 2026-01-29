<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'work_order_no' => $this->work_order_no,
            'work_order_year' => $this->work_order_year,
            'date' => $this->date->format('Y-m-d'),
            'customer_id' => $this->customer_id,
            'customer' => [
                'id' => $this->customer_id,
                'name' => $this->customer?->name,
                'phone' => $this->customer?->phone_number,
            ],
            'customer_location_id' => $this->customer_location_id,
            'work_location' => $this->customerLocation?->location_name,
            'scope_of_work' => $this->scope_of_work,
            'employees' => $this->whenLoaded('employees', function () {
                return $this->employees->map(function ($employee) {
                    return [
                        'id' => $employee->pivot->id,
                        'employee_id' => $employee->id,
                        'name' => $employee->name,
                        'position' => $employee->position,
                    ];
                });
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
