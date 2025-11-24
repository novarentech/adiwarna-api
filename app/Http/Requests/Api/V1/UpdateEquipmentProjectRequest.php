<?php

namespace App\Http\Requests\Api\V1;

class UpdateEquipmentProjectRequest extends StoreEquipmentProjectRequest
{
    public function rules(): array
    {
        return [
            'customer_id' => 'sometimes|required|exists:customers,id',
            'customer_location_id' => 'sometimes|nullable|exists:customer_locations,id',
            'project_date' => 'sometimes|required|date',
            'prepared_by' => 'sometimes|required|string|max:255',
            'verified_by' => 'sometimes|required|string|max:255',
            'equipment_ids' => 'sometimes|required|array|min:1',
            'equipment_ids.*' => 'required|exists:equipment_general,id',
        ];
    }
}
