<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuotationListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date->format('Y-m-d'),
            'ref_no' => $this->ref_no,
            'ref_year' => $this->ref_year,
            'customer' => $this->customer ? $this->customer->name : null,
            'pic_name' => $this->pic_name,
            'subject' => $this->subject,
        ];
    }
}
