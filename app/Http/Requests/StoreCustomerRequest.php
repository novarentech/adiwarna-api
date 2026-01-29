<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_no' => 'required|string|max:50|unique:customers',
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'customer_locations' => 'nullable|array',
            'customer_locations.*.location_name' => 'required_with:customer_locations|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_no.required' => 'Customer number is required',
            'customer_no.unique' => 'Customer number already exists',
            'name.required' => 'Customer name is required',
            'phone_number.required' => 'Phone number is required',
            'address.required' => 'Address is required',
        ];
    }
}
