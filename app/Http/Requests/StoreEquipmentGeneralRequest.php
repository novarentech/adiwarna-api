<?php

namespace App\Http\Requests;

use App\Enums\CalibrationAgency;
use App\Enums\CalibrationDuration;
use App\Enums\EquipmentCondition;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEquipmentGeneralRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => 'required|string|max:255',
            'merk_type' => 'required|string|max:255',
            'serial_number' => 'required|string|max:100|unique:equipment_general,serial_number',
            'calibration_date' => 'required|date',
            'duration_months' => ['required', Rule::enum(CalibrationDuration::class)],
            'calibration_agency' => ['required', Rule::enum(CalibrationAgency::class)],
            'condition' => ['required', Rule::enum(EquipmentCondition::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'description.required' => 'Description is required',
            'merk_type.required' => 'Merk/Type is required',
            'serial_number.required' => 'Serial number is required',
            'serial_number.unique' => 'Serial number already exists',
            'calibration_date.required' => 'Calibration date is required',
            'duration_months.required' => 'Duration is required',
            'calibration_agency.required' => 'Calibration agency is required',
            'condition.required' => 'Condition is required',
        ];
    }
}
