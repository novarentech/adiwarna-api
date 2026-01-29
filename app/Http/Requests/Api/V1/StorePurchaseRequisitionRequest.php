<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequisitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pr_no' => 'required|string|max:50',
            'date' => 'required|date',
            'po_no_cash' => 'nullable|string|max:100',
            'supplier' => 'required|string|in:online,offline',
            'routing' => 'required|string|in:online,offline',
            'vat_percentage' => 'nullable|integer|min:0|max:100',
            'requested_by' => 'nullable|string|max:255',
            'requested_position' => 'nullable|string|max:255',
            'approved_by' => 'nullable|string|max:255',
            'approved_position' => 'nullable|string|max:255',
            'authorized_by' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:draft,pending,approved,rejected',
            'items' => 'required|array|min:1|max:10',
            'items.*.qty' => 'required|numeric|min:0',
            'items.*.unit' => 'required|string|max:50',
            'items.*.description' => 'required|string',
            'items.*.unit_price' => 'required|numeric|min:0',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->supplier !== $this->routing) {
                $validator->errors()->add('supplier', 'Supplier must match routing value (both online or both offline)');
                $validator->errors()->add('routing', 'Routing must match supplier value (both online or both offline)');
            }
        });
    }

    public function messages(): array
    {
        return [
            'pr_no.required' => 'PR number is required',
            'date.required' => 'PR date is required',
            'supplier.required' => 'Supplier is required',
            'supplier.in' => 'Supplier must be either online or offline',
            'routing.required' => 'Routing is required',
            'routing.in' => 'Routing must be either online or offline',
            'items.required' => 'At least one item is required',
            'items.max' => 'Maximum 10 items allowed',
            'items.*.qty.required' => 'Item quantity is required',
            'items.*.unit.required' => 'Item unit is required',
            'items.*.description.required' => 'Item description is required',
            'items.*.unit_price.required' => 'Item unit price is required',
        ];
    }
}
