<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\CalibrationAgency;
use App\Enums\CalibrationDuration;
use App\Enums\EquipmentCondition;
use Illuminate\Validation\Rule;

class UpdateEquipmentGeneralRequest extends StoreEquipmentGeneralRequest
{
    public function rules(): array
    {
        $equipmentId = $this->route('equipment_general');

        return [
            'description' => 'sometimes|required|string|max:255',
            'merk_type' => 'sometimes|required|string|max:255',
            'serial_number' => ['sometimes', 'required', 'string', 'max:100', Rule::unique('equipment_general', 'serial_number')->ignore($equipmentId)],
            'calibration_date' => 'sometimes|required|date',
            'duration_months' => ['sometimes', 'required', Rule::enum(CalibrationDuration::class)],
            'calibration_agency' => ['sometimes', 'required', Rule::enum(CalibrationAgency::class)],
            'condition' => ['sometimes', 'required', Rule::enum(EquipmentCondition::class)],
        ];
    }
}
