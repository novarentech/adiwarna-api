<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseRequisitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pr_no' => 'sometimes|required|string|max:50',
            'rev_no' => 'nullable|string|max:50',
            'date' => 'sometimes|required|date',
            'required_delivery' => 'sometimes|required|date',
            'po_no_cash' => 'nullable|string|max:100',
            'supplier' => 'sometimes|required|string|max:255',
            'place_of_delivery' => 'sometimes|required|string|max:255',
            'routing' => 'nullable|string|in:online,offline',
            'vat_percentage' => 'nullable|numeric|min:0|max:100',
            'requested_by' => 'nullable|string|max:255',
            'approved_by' => 'nullable|string|max:255',
            'authorized_by' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:draft,pending,approved,rejected',
            'notes' => 'nullable|string',
            'items' => 'sometimes|array|min:1|max:10',
            'items.*.id' => 'nullable|integer|exists:purchase_requisition_items,id',
            'items.*.qty' => 'required|numeric|min:0',
            'items.*.unit' => 'required|string|max:50',
            'items.*.description' => 'required|string',
            'items.*.unit_price' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'pr_no.required' => 'PR number is required',
            'date.required' => 'PR date is required',
            'required_delivery.required' => 'Required delivery date is required',
            'supplier.required' => 'Supplier is required',
            'place_of_delivery.required' => 'Place of delivery is required',
            'items.min' => 'At least one item is required',
            'items.max' => 'Maximum 10 items allowed',
            'items.*.qty.required' => 'Item quantity is required',
            'items.*.unit.required' => 'Item unit is required',
            'items.*.description.required' => 'Item description is required',
            'items.*.unit_price.required' => 'Item unit price is required',
        ];
    }
}
