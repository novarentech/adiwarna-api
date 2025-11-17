<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuotationRequest extends FormRequest
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
            'valid_until' => 'nullable|date',
            'clause' => 'nullable|string',
            'workday' => 'nullable|string|max:255',
            'auth_name' => 'nullable|string|max:255',
            'auth_position' => 'nullable|string|max:255',
            'discount' => 'nullable|numeric|min:0|max:100',
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|exists:quotation_items,id',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit' => 'required|string|max:50',
            'items.*.rate' => 'required|numeric|min:0',
        ];
    }
}
