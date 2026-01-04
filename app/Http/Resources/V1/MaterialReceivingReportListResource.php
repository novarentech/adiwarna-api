<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaterialReceivingReportListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'po_no' => $this->po_no,
            'po_year' => $this->po_year,
            'supplier' => $this->supplier,
            'receiving_date' => $this->receiving_date->format('d/m/Y'),
            'order_by' => $this->order_by?->label() ?? 'Offline',
            'total_items' => $this->items_count ?? 0,
            'received_by' => $this->received_by,
            'status' => $this->status?->label() ?? 'Complete',
        ];
    }
}
