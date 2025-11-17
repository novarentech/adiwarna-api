<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'assignment_no' => 'required|string|max:50',
            'assignment_year' => 'required|integer|min:2000|max:2100',
            'date' => 'required|date',
            'ref_no' => 'nullable|string|max:50',
            'ref_year' => 'nullable|integer',
            'customer_id' => 'required|exists:customers,id',
            'customer_location_id' => 'nullable|exists:customer_locations,id',
            'ref_po_no_instruction' => 'nullable|string|max:255',
            'location' => 'nullable|string',
            'scope' => 'nullable|string',
            'estimation' => 'nullable|string|max:255',
            'mobilization' => 'nullable|date',
            'auth_name' => 'nullable|string|max:255',
            'auth_pos' => 'nullable|string|max:255',
            'employees' => 'nullable|array',
            'employees.*.employee_id' => 'required|exists:employees,id',
            'employees.*.detail' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'assignment_no.required' => 'Assignment number is required',
            'date.required' => 'Assignment date is required',
            'customer_id.required' => 'Customer is required',
            'customer_id.exists' => 'Selected customer does not exist',
        ];
    }
}
