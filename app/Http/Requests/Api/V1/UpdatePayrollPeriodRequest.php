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
            'payroll_project_id' => 'required|exists:payroll_projects,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
            'name' => 'required|string|max:255',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'status' => 'required|string|max:50',
            'enable_progressive_ot' => 'boolean',
            'enable_bpjs' => 'boolean',
            'enable_pph21' => 'boolean',
            'bpjs_jht_rate' => 'nullable|numeric|min:0|max:100',
            'bpjs_pensiun_rate' => 'nullable|numeric|min:0|max:100',
            'bpjs_kesehatan_rate' => 'nullable|numeric|min:0|max:100',
        ];
    }
}
