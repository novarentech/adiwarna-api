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
            'customer_location_id' => 'required|exists:customer_locations,id',
            'ref_po_no_instruction' => 'nullable|string|max:255',
            'scope' => 'nullable|string',
            'estimation' => 'nullable|string|max:255',
            'mobilization' => 'nullable|string|max:255',
            'auth_name' => 'nullable|string|max:255',
            'auth_pos' => 'nullable|string|max:255',
            'workers' => 'nullable|array',
            'workers.*.worker_name' => 'required_with:workers|string|max:255',
            'workers.*.position' => 'required_with:workers|string|max:255',
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
