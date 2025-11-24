<?php

namespace Database\Factories;

use App\Enums\CalibrationAgency;
use App\Enums\CalibrationDuration;
use App\Enums\EquipmentCondition;
use App\Models\EquipmentGeneral;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class EquipmentGeneralFactory extends Factory
{
    protected $model = EquipmentGeneral::class;

    public function definition(): array
    {
        $descriptions = ['Profile Gauge', 'Thickness Gauge', 'Pressure Gauge', 'Temperature Gauge', 'Flow Meter'];
        $merks = ['Gagemaker', 'Lonestar', 'Mitutoyo', 'Starrett', 'Testo'];
        $calibrationDate = fake()->dateTimeBetween('-1 year', 'now');
        $duration = fake()->randomElement([CalibrationDuration::SIX_MONTHS, CalibrationDuration::TWELVE_MONTHS]);

        return [
            'description' => fake()->randomElement($descriptions),
            'merk_type' => fake()->randomElement($merks) . ' - ' . fake()->bothify('??###'),
            'serial_number' => fake()->unique()->bothify('??###-TC#-#'),
            'calibration_date' => $calibrationDate,
            'duration_months' => $duration,
            'expired_date' => Carbon::parse($calibrationDate)->addMonths($duration->value),
            'calibration_agency' => fake()->randomElement([CalibrationAgency::INTERNAL, CalibrationAgency::EXTERNAL]),
            'condition' => fake()->randomElement([EquipmentCondition::OK, EquipmentCondition::REPAIR, EquipmentCondition::REJECT]),
        ];
    }
}
