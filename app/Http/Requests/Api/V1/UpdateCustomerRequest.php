<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_no' => [
                'sometimes',
                'required',
                'string',
                'max:50',
                Rule::unique('customers')->ignore($this->route('customer'))
            ],
            'name' => 'sometimes|required|string|max:255',
            'phone_number' => 'sometimes|required|string|max:20',
            'address' => 'sometimes|required|string',
            'customer_locations' => 'sometimes|array',
            'customer_locations.*.id' => 'nullable|exists:customer_locations,id',
            'customer_locations.*.location_name' => 'required_with:customer_locations|string|max:255',
        ];
    }
}
