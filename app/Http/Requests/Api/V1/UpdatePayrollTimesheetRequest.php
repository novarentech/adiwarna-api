<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\AttendanceStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePayrollTimesheetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payroll_employee_id' => 'required|exists:payroll_employees,id',
            'date' => 'required|date',
            'attendance_status' => ['required', Rule::enum(AttendanceStatus::class)],
            'regular_hours' => 'nullable|numeric|min:0|max:24',
            'overtime_hours' => 'nullable|numeric|min:0|max:24',
            'total_allowances' => 'nullable|numeric|min:0',
            'late_minutes' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ];
    }
}
