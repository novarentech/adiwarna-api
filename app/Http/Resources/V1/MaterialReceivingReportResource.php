<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaterialReceivingReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'rr_no' => $this->rr_no,
            'rr_year' => $this->rr_year,
            'date' => $this->date,
            'ref_pr_no' => $this->ref_pr_no,
            'ref_po_no' => $this->ref_po_no,
            'supplier' => $this->supplier,
            'receiving_date' => $this->receiving_date,
            'notes' => $this->notes,
            'items' => MaterialReceivingReportItemResource::collection($this->whenLoaded('items')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
