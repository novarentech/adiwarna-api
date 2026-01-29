<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_no' => [
                'required',
                'string',
                'max:50',
                Rule::unique('employees')->ignore($this->route('employee'))
            ],
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
        ];
    }
}
