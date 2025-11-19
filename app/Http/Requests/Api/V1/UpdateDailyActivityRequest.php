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
            'customer_id' => 'required|exists:customers,id',
            'date' => 'required|date',
            'location' => 'nullable|string',
            'time_from' => 'nullable|date_format:H:i',
            'time_to' => 'nullable|date_format:H:i|after:time_from',
            'prepared_name' => 'nullable|string|max:255',
            'prepared_pos' => 'nullable|string|max:255',
            'acknowledge_name' => 'nullable|string|max:255',
            'acknowledge_pos' => 'nullable|string|max:255',
            'members' => 'nullable|array',
            'members.*.id' => 'nullable|exists:daily_activity_members,id',
            'members.*.name' => 'required|string|max:255',
            'members.*.position' => 'nullable|string|max:255',
            'descriptions' => 'nullable|array',
            'descriptions.*.id' => 'nullable|exists:daily_activity_descriptions,id',
            'descriptions.*.description' => 'required|string',
        ];
    }
}
