<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    public function definition(): array
    {
        $month = str_pad(fake()->numberBetween(1, 12), 2, '0', STR_PAD_LEFT);
        $monthRoman = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'][$month - 1];
        $year = fake()->year();
        $taNumber = str_pad(fake()->unique()->numberBetween(1, 999), 3, '0', STR_PAD_LEFT);

        return [
            'name' => fake()->sentence(3),
            'ta_no' => "{$taNumber}/{$monthRoman}/{$year}",
            'date' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'customer_id' => Customer::factory(),
            'pic_name' => fake()->name(),
            'pic_phone' => fake()->phoneNumber(),
            'pic_district' => fake()->city(),
            'report_type' => fake()->randomElement([
                'Maintenance Report',
                'Installation Report',
                'Inspection Report',
                'Service Report',
            ]),
        ];
    }
}
