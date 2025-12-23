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
            'po_inv_pr_no' => 'required|string|max:50',
            'supplier' => 'required|string|max:255',
            'receiving_date' => 'required|date',
            'order_by' => 'nullable|string|in:online,offline',
            'received_by' => 'nullable|string|max:255',
            'acknowledge_by' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:complete,partial',
            'notes' => 'nullable|string',
            'items' => 'sometimes|array|min:1|max:10',
            'items.*.id' => 'nullable|integer|exists:material_receiving_report_items,id',
            'items.*.description' => 'required|string',
            'items.*.order_qty' => 'required|numeric|min:0',
            'items.*.received_qty' => 'required|numeric|min:0',
            'items.*.remarks' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'po_inv_pr_no.required' => 'PO/INV/PR number is required',
            'supplier.required' => 'Supplier is required',
            'receiving_date.required' => 'Receiving date is required',
            'items.min' => 'At least one item is required',
            'items.max' => 'Maximum 10 items allowed',
            'items.*.description.required' => 'Item description is required',
            'items.*.order_qty.required' => 'Order quantity is required',
            'items.*.received_qty.required' => 'Received quantity is required',
        ];
    }
}
