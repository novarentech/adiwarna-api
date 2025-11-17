<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaterialReceivingReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rr_no' => 'required|string|max:50',
            'rr_year' => 'required|string|max:4',
            'date' => 'required|date',
            'ref_pr_no' => 'nullable|string|max:50',
            'ref_po_no' => 'nullable|string|max:50',
            'supplier' => 'required|string|max:255',
            'receiving_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1|max:5',
            'items.*.description' => 'required|string',
            'items.*.order_qty' => 'required|numeric|min:0',
            'items.*.received_qty' => 'required|numeric|min:0',
            'items.*.remarks' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'rr_no.required' => 'RR number is required',
            'rr_year.required' => 'RR year is required',
            'date.required' => 'RR date is required',
            'supplier.required' => 'Supplier is required',
            'receiving_date.required' => 'Receiving date is required',
            'items.required' => 'At least one item is required',
            'items.max' => 'Maximum 5 items allowed',
            'items.*.description.required' => 'Item description is required',
            'items.*.order_qty.required' => 'Order quantity is required',
            'items.*.received_qty.required' => 'Received quantity is required',
        ];
    }
}
