<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'po_no' => 'required|string|max:50',
            'po_year' => 'required|integer|min:2000|max:2100',
            'date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'pic_name' => 'nullable|string|max:255',
            'pic_phone' => 'nullable|string|max:20',
            'required_date' => 'nullable|date',
            'top_dp' => 'nullable|string|max:255',
            'top_cod' => 'nullable|string|max:255',
            'quotation_ref' => 'nullable|string|max:255',
            'purchase_requisition_no' => 'nullable|string|max:50',
            'purchase_requisition_year' => 'nullable|integer',
            'discount' => 'nullable|numeric|min:0|max:100',
            'req_name' => 'nullable|string|max:255',
            'req_pos' => 'nullable|string|max:255',
            'app_name' => 'nullable|string|max:255',
            'app_pos' => 'nullable|string|max:255',
            'auth_name' => 'nullable|string|max:255',
            'auth_pos' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit' => 'required|string|max:50',
            'items.*.rate' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'po_no.required' => 'PO number is required',
            'date.required' => 'PO date is required',
            'customer_id.required' => 'Customer is required',
            'customer_id.exists' => 'Selected customer does not exist',
            'items.required' => 'At least one item is required',
        ];
    }
}
