<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkAssignmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'assignment_no' => $this->assignment_no,
            'assignment_year' => $this->assignment_year,
            'date' => $this->date->format('Y-m-d'),
            'ref_no' => $this->ref_no,
            'ref_year' => $this->ref_year,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'customer_location' => $this->whenLoaded('customerLocation'),
            'ref_po_no_instruction' => $this->ref_po_no_instruction,
            'scope' => $this->scope,
            'estimation' => $this->estimation,
            'mobilization' => $this->mobilization,
            'auth_name' => $this->auth_name,
            'auth_pos' => $this->auth_pos,
            'workers' => $this->whenLoaded('workers', function () {
                return $this->workers->map(function ($worker) {
                    return [
                        'id' => $worker->id,
                        'worker_name' => $worker->worker_name,
                        'position' => $worker->position,
                    ];
                });
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
