<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'ta_no' => $this->ta_no,
            'date' => $this->date->format('Y-m-d'),
            'customer' => [
                'id' => $this->customer_id,
                'name' => $this->customer?->name,
                'address' => $this->customer?->address,
                'phone' => $this->customer?->phone_number,
            ],
            'pic' => [
                'name' => $this->pic_name,
                'phone' => $this->pic_phone,
                'district' => $this->pic_district,
            ],
            'report_type' => $this->report_type,
            'work_orders' => $this->whenLoaded('workOrders', function () {
                return $this->workOrders->map(function ($workOrder) {
                    return [
                        'id' => $workOrder->id,
                        'wo_number' => $workOrder->wo_number,
                        'wo_year' => $workOrder->wo_year,
                        'location' => $workOrder->location,
                    ];
                });
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
