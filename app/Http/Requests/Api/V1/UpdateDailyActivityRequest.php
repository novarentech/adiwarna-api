<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDailyActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'po_no' => 'nullable|string|max:50',
            'po_year' => 'nullable|integer',
            'ref_no' => 'nullable|string|max:50',
            'customer_id' => 'sometimes|required|exists:customers,id',
            'date' => 'sometimes|required|date',
            'location' => 'nullable|string',
            'time_from' => 'nullable|date_format:H:i',
            'time_to' => 'nullable|date_format:H:i|after:time_from',
            'prepared_name' => 'nullable|string|max:255',
            'prepared_pos' => 'nullable|string|max:255',
            'acknowledge_name' => 'nullable|string|max:255',
            'acknowledge_pos' => 'nullable|string|max:255',
            'members' => 'sometimes|array',
            'members.*.id' => 'nullable|exists:daily_activity_members,id',
            'members.*.employee_id' => 'required_with:members|exists:employees,id',
            'descriptions' => 'sometimes|array',
            'descriptions.*.id' => 'nullable|exists:daily_activity_descriptions,id',
            'descriptions.*.description' => 'required_with:descriptions|string',
            'descriptions.*.equipment_no' => 'nullable|string|max:255',
        ];
    }
}
