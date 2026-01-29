<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'work_order_no' => 'sometimes|string|max:50',
            'work_order_year' => 'sometimes|integer|min:2000|max:2100',
            'date' => 'sometimes|date',
            'customer_id' => 'sometimes|exists:customers,id',
            'customer_location_id' => 'nullable|exists:customer_locations,id',
            'scope_of_work' => 'sometimes|array|min:1',
            'scope_of_work.*' => 'required_with:scope_of_work|string',
            'employees' => 'sometimes|array|min:1',
            'employees.*.id' => 'nullable|exists:work_order_employees,id',
            'employees.*.employee_id' => 'required_with:employees|exists:employees,id',
        ];
    }

    public function messages(): array
    {
        return [
            'work_order_no.required' => 'Work order number is required',
            'work_order_year.required' => 'Work order year is required',
            'date.required' => 'Work order date is required',
            'customer_id.required' => 'Customer is required',
            'customer_id.exists' => 'Selected customer does not exist',
            'customer_location_id.exists' => 'Selected work location does not exist',
            'scope_of_work.required' => 'At least one scope of work is required',
            'employees.required' => 'At least one employee is required',
            'employees.*.employee_id.required' => 'Employee is required',
            'employees.*.employee_id.exists' => 'Selected employee does not exist',
        ];
    }
}
