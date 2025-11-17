<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentTransmittalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'customer_name' => 'required|string|max:255',
            'customer_address' => 'required|string',
            'pic_name' => 'required|string|max:100',
            'pic_phone' => 'required|string|max:20',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'documents' => 'required|array|min:1|max:5',
            'documents.*.work_reference' => 'required|string',
            'documents.*.document_no' => 'required|string',
            'documents.*.document_year' => 'required|string|max:4',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Customer is required',
            'customer_name.required' => 'Customer name is required',
            'customer_address.required' => 'Customer address is required',
            'pic_name.required' => 'PIC name is required',
            'pic_phone.required' => 'PIC phone is required',
            'date.required' => 'Transmittal date is required',
            'documents.required' => 'At least one document is required',
            'documents.max' => 'Maximum 5 documents allowed',
            'documents.*.work_reference.required' => 'Work reference is required',
            'documents.*.document_no.required' => 'Document number is required',
            'documents.*.document_year.required' => 'Document year is required',
        ];
    }
}
