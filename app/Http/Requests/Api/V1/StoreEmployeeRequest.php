<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_no' => 'required|string|max:50|unique:employees',
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
        ];
    }
}
