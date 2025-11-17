<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreEquipmentProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_name' => 'required|string|max:255',
            'customer_id' => 'required|exists:customers,id',
            'equipment_name' => 'required|string|max:255',
            'equipment_type' => 'required|string|max:100',
            'quantity' => 'required|integer|min:1',
            'condition' => 'required|string|in:good,fair,poor',
            'assigned_date' => 'required|date',
            'return_date' => 'nullable|date|after:assigned_date',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'project_name.required' => 'Project name is required',
            'customer_id.required' => 'Customer is required',
            'customer_id.exists' => 'Selected customer does not exist',
            'equipment_name.required' => 'Equipment name is required',
            'equipment_type.required' => 'Equipment type is required',
            'quantity.required' => 'Quantity is required',
            'condition.required' => 'Condition is required',
            'assigned_date.required' => 'Assigned date is required',
            'return_date.after' => 'Return date must be after assigned date',
        ];
    }
}
