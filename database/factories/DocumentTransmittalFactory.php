<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\DocumentTransmittal;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentTransmittalFactory extends Factory
{
    protected $model = DocumentTransmittal::class;

    public function definition(): array
    {
        $months = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $year = fake()->year();
        $month = fake()->randomElement($months);
        $number = str_pad(fake()->numberBetween(1, 999), 3, '0', STR_PAD_LEFT);

        return [
            'name' => fake()->sentence(3),
            'ta_no' => "{$number}/{$month}/{$year}",
            'date' => fake()->dateTimeBetween('-6 months', 'now'),
            'customer_id' => Customer::factory(),
            'customer_district' => fake()->optional()->city(),
            'pic_name' => fake()->name(),
            'report_type' => fake()->randomElement(['Monthly Report', 'Quarterly Report', 'Annual Report', 'Project Report']),
        ];
    }
}
