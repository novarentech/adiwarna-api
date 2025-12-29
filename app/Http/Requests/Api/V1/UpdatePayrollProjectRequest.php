<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\PayrollProjectStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePayrollProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'company_name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => ['sometimes', 'required', Rule::enum(PayrollProjectStatus::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Project name is required',
            'company_name.required' => 'Company name is required',
            'start_date.required' => 'Start date is required',
            'end_date.after_or_equal' => 'End date must be after or equal to start date',
        ];
    }
}
