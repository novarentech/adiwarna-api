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
            'date' => 'sometimes|required|date',
            'ref_no' => 'sometimes|required|string|max:50',
            'ref_year' => 'sometimes|required|integer|min:2000|max:2100',
            'customer_id' => 'sometimes|required|exists:customers,id',
            'pic_name' => 'nullable|string|max:255',
            'pic_phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string',
            'top' => 'nullable|string|max:255',
            'valid_until' => 'nullable|integer|min:1|max:1000',
            'clause' => 'nullable|string',
            'workday' => 'nullable|string|max:255',
            'auth_name' => 'nullable|string|max:255',
            'auth_position' => 'nullable|string|max:255',
            'discount' => 'nullable|numeric|min:0|max:100',
            'items' => 'sometimes|array|min:1',
            'items.*.id' => 'nullable|exists:quotation_items,id',
            'items.*.description' => 'required_with:items|string',
            'items.*.quantity' => 'required_with:items|numeric|min:0',
            'items.*.unit' => 'required_with:items|string|max:50',
            'items.*.rate' => 'required_with:items|numeric|min:0',
            'adiwarnas' => 'sometimes|array',
            'adiwarnas.*.id' => 'nullable|exists:quotation_adiwarnas,id',
            'adiwarnas.*.adiwarna_description' => 'required_with:adiwarnas|string',
            'clients' => 'sometimes|array',
            'clients.*.id' => 'nullable|exists:quotation_clients,id',
            'clients.*.client_description' => 'required_with:clients|string',
        ];
    }
}
