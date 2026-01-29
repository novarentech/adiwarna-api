<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseRequisitionListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'pr_no' => $this->pr_no,
            'pr_date' => $this->pr_date,
            'date' => $this->date->format('d/m/Y'),
            'supplier' => $this->supplier?->label() ?? 'Unknown',
            'total_amount' => 'Rp ' . number_format($this->total_amount, 3, '.', '.'),
            'status' => $this->status?->label() ?? 'Draft',
        ];
    }
}
