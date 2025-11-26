<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class GeneratePayrollSlipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'employee_id' => ['nullable', 'integer', 'exists:payroll_employees,id'],
            'slip_type' => ['nullable', 'string', 'in:monthly,weekly,daily'],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'employee_id.integer' => 'Employee ID must be a valid number',
            'employee_id.exists' => 'The selected employee does not exist',
            'slip_type.in' => 'Slip type must be one of: monthly, weekly, daily',
        ];
    }
}
