<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreEquipmentGeneralRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'equipment_name' => 'required|string|max:255',
            'equipment_type' => 'required|string|max:100',
            'quantity' => 'required|integer|min:1',
            'condition' => 'required|string|in:good,fair,poor',
            'specifications' => 'nullable|array',
            'purchase_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'equipment_name.required' => 'Equipment name is required',
            'equipment_type.required' => 'Equipment type is required',
            'quantity.required' => 'Quantity is required',
            'quantity.min' => 'Quantity must be at least 1',
            'condition.required' => 'Condition is required',
            'condition.in' => 'Condition must be good, fair, or poor',
        ];
    }
}
