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
            'pr_year' => 'required|string|max:4',
            'date' => 'required|date',
            'supplier' => 'required|string|max:255',
            'delivery_place' => 'required|string',
            'discount' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1|max:5',
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
            'pr_year.required' => 'PR year is required',
            'date.required' => 'PR date is required',
            'supplier.required' => 'Supplier is required',
            'delivery_place.required' => 'Delivery place is required',
            'items.required' => 'At least one item is required',
            'items.max' => 'Maximum 5 items allowed',
            'items.*.qty.required' => 'Item quantity is required',
            'items.*.unit.required' => 'Item unit is required',
            'items.*.description.required' => 'Item description is required',
            'items.*.unit_price.required' => 'Item unit price is required',
        ];
    }
}
