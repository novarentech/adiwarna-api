<?php

namespace App\Http\Requests;

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
            'customer_id' => 'required|exists:customers,id',
            'customer_location_id' => 'nullable|exists:customer_locations,id',
            'project_date' => 'required|date',
            'prepared_by' => 'required|string|max:255',
            'verified_by' => 'required|string|max:255',
            'equipment_ids' => 'required|array|min:1',
            'equipment_ids.*' => 'required|exists:equipment_general,id',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Customer is required',
            'customer_id.exists' => 'Selected customer does not exist',
            'customer_location_id.exists' => 'Selected location does not exist',
            'project_date.required' => 'Project date is required',
            'prepared_by.required' => 'Prepared by is required',
            'verified_by.required' => 'Verified by is required',
            'equipment_ids.required' => 'At least one equipment is required',
            'equipment_ids.*.exists' => 'Selected equipment does not exist',
        ];
    }
}
