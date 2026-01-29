<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuotationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'ref_no' => 'required|string|max:50',
            'ref_year' => 'required|integer|min:2000|max:2100',
            'customer_id' => 'required|exists:customers,id',
            'pic_name' => 'nullable|string|max:255',
            'pic_phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string',
            'top' => 'nullable|string|max:255',
            'valid_until' => 'nullable|integer|min:1|max:1000',
            'clause' => 'nullable|string',
            'workday' => 'nullable|string|max:255',
            'auth_name' => 'nullable|string|max:255',
            'auth_position' => 'nullable|string|max:255',
            'discount' => 'nullable|integer|min:0|max:100',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit' => 'required|string|max:50',
            'items.*.rate' => 'required|numeric|min:0',
            'adiwarnas' => 'nullable|array',
            'adiwarnas.*.adiwarna_description' => 'required|string',
            'clients' => 'nullable|array',
            'clients.*.client_description' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'date.required' => 'Quotation date is required',
            'ref_no.required' => 'Reference number is required',
            'customer_id.required' => 'Customer is required',
            'customer_id.exists' => 'Selected customer does not exist',
            'items.required' => 'At least one item is required',
            'items.*.description.required' => 'Item description is required',
            'items.*.quantity.required' => 'Item quantity is required',
            'items.*.unit.required' => 'Item unit is required',
            'items.*.rate.required' => 'Item rate is required',
        ];
    }
}
