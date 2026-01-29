<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrackRecordResource extends JsonResource
{
    /**
     * Transform work order data into track record format.
     */
    public function toArray(Request $request): array
    {
        // Get worker names from employees relationship
        $workerNames = $this->whenLoaded('employees', function () {
            return $this->employees->pluck('name')->join(', ');
        }, '');

        // Get scope of work as comma-separated string
        $scopeOfWork = is_array($this->scope_of_work)
            ? implode(', ', $this->scope_of_work)
            : $this->scope_of_work;

        return [
            'work_order_no' => $this->work_order_no,
            'work_order_year' => $this->work_order_year,
            'date_started' => $this->date->format('Y-m-d'),
            'workers_name' => $workerNames,
            'scope_of_work' => $scopeOfWork,
            'customer' => $this->customer?->name,
            'work_location' => $this->customerLocation?->location_name,
        ];
    }
}
