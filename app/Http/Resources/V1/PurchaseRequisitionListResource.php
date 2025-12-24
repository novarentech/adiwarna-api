<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseRequisitionListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'pr_no' => $this->pr_no,
            'rev_no' => $this->rev_no,
            'required_delivery' => $this->required_delivery->format('d/m/Y'),
            'supplier' => $this->supplier,
            'place_of_delivery' => $this->place_of_delivery,
            'total_amount' => 'Rp ' . number_format($this->total_amount, 3, '.', '.'),
            'status' => $this->status?->label() ?? 'Draft',
        ];
    }
}
