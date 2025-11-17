<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransmittalDocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'transmittal_id' => $this->transmittal_id,
            'work_reference' => $this->work_reference,
            'document_no' => $this->document_no,
            'document_year' => $this->document_year,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
