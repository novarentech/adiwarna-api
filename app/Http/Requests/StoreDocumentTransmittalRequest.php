<?php

namespace App\Http\Requests;

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
            'name' => 'required|string|max:255',
            'ta_no' => 'required|string|regex:/^\d{3}\/[IVX]+\/\d{4}$/',
            'date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'customer_district' => 'nullable|string|max:255',
            'pic_name' => 'required|string|max:255',
            'report_type' => 'required|string|max:255',
            'documents' => 'required|array|min:1',
            'documents.*.wo_number' => 'required|string',
            'documents.*.wo_year' => 'required|integer|min:2000|max:2100',
            'documents.*.location' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Transmittal name is required',
            'ta_no.required' => 'TA No is required',
            'ta_no.regex' => 'TA No format must be: number/month/year (e.g., 001/VII/2024)',
            'date.required' => 'Transmittal date is required',
            'customer_id.required' => 'Customer is required',
            'customer_id.exists' => 'Selected customer does not exist',
            'pic_name.required' => 'PIC name is required',
            'report_type.required' => 'Report type is required',
            'documents.required' => 'At least one document is required',
            'documents.*.wo_number.required' => 'WO number is required',
            'documents.*.wo_year.required' => 'WO year is required',
            'documents.*.location.required' => 'Location is required',
        ];
    }
}
