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
            'delivery_note_no' => 'required|string|max:50',
            'date' => 'required|date',
            'customer' => 'required|string|max:255',
            'customer_address' => 'required|string',
            'wo_no' => 'required|string|max:50',
            'delivered_with' => 'nullable|string|max:255',
            'vehicle_plate' => 'required|string|max:50',
            'delivered_by' => 'nullable|string|max:255',
            'received_by' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:pending,delivered,cancelled',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1|max:20',
            'items.*.id' => 'nullable|integer|exists:delivery_note_items,id',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.serial_number' => 'nullable|string|max:100',
            'items.*.qty' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'delivery_note_no.required' => 'Delivery note number is required',
            'date.required' => 'Date is required',
            'customer.required' => 'Customer is required',
            'customer_address.required' => 'Customer address is required',
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
