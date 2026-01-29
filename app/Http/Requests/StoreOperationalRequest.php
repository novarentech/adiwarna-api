<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOperationalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'type' => 'required|string|max:100',
            'description' => 'required|string',
            'amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'date.required' => 'Date is required',
            'type.required' => 'Type is required',
            'description.required' => 'Description is required',
            'amount.min' => 'Amount must be at least 0',
        ];
    }
}
