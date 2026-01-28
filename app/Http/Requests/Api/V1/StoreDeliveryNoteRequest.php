<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeliveryNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'dn_no' => 'required|string|max:50',
            'isOther' => 'nullable|boolean',
            'name' => 'nullable|string|present_if:isOther,true',
            'address' => 'nullable|string|present_if:isOther,true',
            'phone' => 'nullable|string|present_if:isOther,true',
            'date' => 'required|date',
            'customer_id' => 'nullable|integer|exists:customers,id|present_if:isOther,false',
            'wo_no' => 'required|string|max:50',
            'delivered_with' => 'nullable|string|max:255',
            'vehicle_plate' => 'required|string|max:50',
            'delivered_by' => 'nullable|string|max:255',
            'received_by' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:pending,delivered,cancelled',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1|max:20',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.serial_number' => 'nullable|string|max:100',
            'items.*.qty' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'dn_no.required' => 'Delivery note number is required',
            'date.required' => 'Date is required',
            'customer_id.required' => 'Customer is required',
            'customer_id.exists' => 'Selected customer does not exist',
            'wo_no.required' => 'Work order number is required',
            'vehicle_plate.required' => 'Vehicle plate is required',
            'items.required' => 'At least one item is required',
            'items.max' => 'Maximum 20 items allowed',
            'items.*.item_name.required' => 'Item name is required',
            'items.*.qty.required' => 'Item quantity is required',
            'items.*.qty.min' => 'Item quantity must be at least 1',
        ];
    }
}
