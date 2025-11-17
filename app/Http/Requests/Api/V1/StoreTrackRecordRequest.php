<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreTrackRecordRequest extends FormRequest
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
            'date' => 'required|date',
            'status' => 'required|string|in:planning,in_progress,completed,on_hold',
            'description' => 'required|string',
            'milestones' => 'nullable|array',
            'milestones.*.title' => 'required_with:milestones|string',
            'milestones.*.date' => 'required_with:milestones|date',
            'milestones.*.status' => 'required_with:milestones|string',
        ];
    }

    public function messages(): array
    {
        return [
            'project_name.required' => 'Project name is required',
            'customer_id.required' => 'Customer is required',
            'customer_id.exists' => 'Selected customer does not exist',
            'date.required' => 'Date is required',
            'status.required' => 'Status is required',
            'status.in' => 'Status must be planning, in_progress, completed, or on_hold',
            'description.required' => 'Description is required',
        ];
    }
}
