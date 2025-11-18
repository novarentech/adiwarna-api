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
        return [
            'customer_id' => Customer::factory(),
            'date' => fake()->dateTimeBetween('-1 month', 'now'),
            'location' => fake()->city(),
            'weather' => fake()->randomElement(['Sunny', 'Cloudy', 'Rainy', 'Windy']),
            'temperature' => fake()->numberBetween(25, 35),
        ];
    }
}
