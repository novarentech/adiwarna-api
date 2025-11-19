<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\DailyActivity;
use Illuminate\Database\Eloquent\Factories\Factory;

class DailyActivityFactory extends Factory
{
    protected $model = DailyActivity::class;

    public function definition(): array
    {
        $date = fake()->dateTimeBetween('-1 month', 'now');
        $year = $date->format('Y');
        $poNo = fake()->unique()->numberBetween(1000, 9999);
        $timeFrom = fake()->time('H:i:s', '12:00:00');
        $timeTo = fake()->time('H:i:s', '18:00:00');

        return [
            'po_no' => 'PO-' . $poNo,
            'po_year' => $year,
            'ref_no' => fake()->optional()->bothify('REF-####'),
            'customer_id' => Customer::factory(),
            'date' => $date,
            'location' => fake()->city(),
            'time_from' => $timeFrom,
            'time_to' => $timeTo,
            'prepared_name' => fake()->name(),
            'prepared_pos' => fake()->jobTitle(),
            'acknowledge_name' => fake()->optional()->name(),
            'acknowledge_pos' => fake()->optional()->jobTitle(),
        ];
    }
}
