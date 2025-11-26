<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\EmployeeCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePayrollEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payroll_period_id' => 'required|exists:payroll_periods,id',
            'employee_name' => 'required|string|max:255',
            'employee_no' => 'required|string|max:50',
            'position' => 'nullable|string|max:255',
            'employee_category' => ['required', Rule::enum(EmployeeCategory::class)],
            'base_salary' => 'required|numeric|min:0',
            'working_hours' => 'nullable|numeric|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
        ];
    }
}
