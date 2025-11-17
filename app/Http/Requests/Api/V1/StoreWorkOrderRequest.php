<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'work_order_no' => 'required|string|max:50',
            'work_order_year' => 'required|string|max:4',
            'date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'description' => 'required|string',
            'status' => 'nullable|string|in:pending,in_progress,completed',
            'employees' => 'required|array|min:1',
            'employees.*.employee_id' => 'required|exists:employees,id',
            'employees.*.detail' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'work_order_no.required' => 'Work order number is required',
            'date.required' => 'Work order date is required',
            'customer_id.required' => 'Customer is required',
            'customer_id.exists' => 'Selected customer does not exist',
            'description.required' => 'Description is required',
            'employees.required' => 'At least one employee is required',
            'employees.*.employee_id.required' => 'Employee is required',
            'employees.*.employee_id.exists' => 'Selected employee does not exist',
        ];
    }
}
