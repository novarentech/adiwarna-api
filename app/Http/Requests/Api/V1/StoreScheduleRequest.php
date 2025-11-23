<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'ta_no' => 'required|string|regex:/^\d{3}\/[IVX]+\/\d{4}$/',
            'date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'pic_name' => 'required|string|max:255',
            'pic_phone' => 'required|string|max:20',
            'pic_district' => 'nullable|string|max:255',
            'report_type' => 'required|string|max:255',
            'work_orders' => 'required|array|min:1',
            'work_orders.*.wo_number' => 'required|string',
            'work_orders.*.wo_year' => 'required|integer|min:2000|max:2100',
            'work_orders.*.location' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Schedule name is required',
            'ta_no.required' => 'TA No is required',
            'ta_no.regex' => 'TA No format must be: number/month/year (e.g., 001/VII/2024)',
            'customer_id.required' => 'Customer is required',
            'customer_id.exists' => 'Selected customer does not exist',
            'date.required' => 'Schedule date is required',
            'pic_name.required' => 'PIC name is required',
            'pic_phone.required' => 'PIC phone is required',
            'report_type.required' => 'Report type is required',
            'work_orders.required' => 'At least one work order is required',
            'work_orders.*.wo_number.required' => 'Work order number is required',
            'work_orders.*.wo_year.required' => 'Work order year is required',
            'work_orders.*.location.required' => 'Work order location is required',
        ];
    }
}
