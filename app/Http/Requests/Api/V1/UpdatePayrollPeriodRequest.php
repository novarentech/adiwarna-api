<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePayrollPeriodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'month' => 'sometimes|required|integer|min:1|max:12',
            'year' => 'sometimes|required|integer|min:2000|max:2100',
            'period_start' => 'sometimes|required|date',
            'period_end' => 'sometimes|required|date|after_or_equal:period_start',
            'status' => 'sometimes|nullable|in:draft,active,closed',
            'enable_progressive_ot' => 'nullable|boolean',
            'enable_bpjs' => 'nullable|boolean',
            'enable_pph21' => 'nullable|boolean',
            'enable_meal_allowance' => 'nullable|boolean',
            'enable_perjadin' => 'nullable|boolean',
            'enable_driver_rules' => 'nullable|boolean',
            'bpjs_jht_rate' => 'nullable|numeric|min:0|max:100',
            'bpjs_pensiun_rate' => 'nullable|numeric|min:0|max:100',
            'bpjs_kesehatan_rate' => 'nullable|numeric|min:0|max:100',
            'bpjs_max_salary_jht' => 'nullable|numeric|min:0',
            'bpjs_max_salary_kesehatan' => 'nullable|numeric|min:0',
            'meal_allowance_rate' => 'nullable|numeric|min:0',
            'perjadin_breakfast_rate' => 'nullable|numeric|min:0',
            'perjadin_lunch_rate' => 'nullable|numeric|min:0',
            'perjadin_dinner_rate' => 'nullable|numeric|min:0',
            'perjadin_daily_rate' => 'nullable|numeric|min:0',
            'perjadin_accommodation_rate' => 'nullable|numeric|min:0',
            'driver_max_payroll_ot' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'month.required' => 'Month is required',
            'year.required' => 'Year is required',
            'period_start.required' => 'Period start date is required',
            'period_end.required' => 'Period end date is required',
            'period_end.after_or_equal' => 'Period end must be after or equal to period start',
        ];
    }
}
