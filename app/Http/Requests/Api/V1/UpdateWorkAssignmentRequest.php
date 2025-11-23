<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'assignment_no' => 'sometimes|required|string|max:50',
            'assignment_year' => 'sometimes|required|integer|min:2000|max:2100',
            'date' => 'sometimes|required|date',
            'ref_no' => 'nullable|string|max:50',
            'ref_year' => 'nullable|integer',
            'customer_id' => 'sometimes|required|exists:customers,id',
            'customer_location_id' => 'sometimes|required|exists:customer_locations,id',
            'ref_po_no_instruction' => 'nullable|string|max:255',
            'scope' => 'nullable|string',
            'estimation' => 'nullable|string|max:255',
            'mobilization' => 'nullable|date',
            'auth_name' => 'nullable|string|max:255',
            'auth_pos' => 'nullable|string|max:255',
            'workers' => 'sometimes|array',
            'workers.*.id' => 'nullable|exists:work_assignment_workers,id',
            'workers.*.worker_name' => 'required_with:workers|string|max:255',
            'workers.*.position' => 'required_with:workers|string|max:255',
        ];
    }
}
