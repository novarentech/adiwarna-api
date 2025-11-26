<?php

namespace App\Http\Requests\Api\V1;

class UpdateDocumentTransmittalRequest extends StoreDocumentTransmittalRequest
{
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'ta_no' => 'sometimes|string|regex:/^\d{3}\/[IVX]+\/\d{4}$/',
            'date' => 'sometimes|date',
            'customer_id' => 'sometimes|exists:customers,id',
            'customer_district' => 'nullable|string|max:255',
            'pic_name' => 'sometimes|string|max:255',
            'report_type' => 'sometimes|string|max:255',
            'documents' => 'sometimes|array|min:1',
            'documents.*.id' => 'nullable|exists:transmittal_documents,id',
            'documents.*.wo_number' => 'required_with:documents|string',
            'documents.*.wo_year' => 'required_with:documents|integer|min:2000|max:2100',
            'documents.*.location' => 'required_with:documents|string',
        ];
    }
}
