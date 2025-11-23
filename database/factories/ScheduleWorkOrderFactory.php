<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\ScheduleWorkOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleWorkOrderFactory extends Factory
{
    protected $model = ScheduleWorkOrder::class;

    public function definition(): array
    {
        $woNumber = str_pad(fake()->numberBetween(1, 999), 3, '0', STR_PAD_LEFT);
        $woYear = fake()->year();

        return [
            'schedule_id' => Schedule::factory(),
            'wo_number' => $woNumber,
            'wo_year' => $woYear,
            'location' => fake()->address(),
        ];
    }
}
