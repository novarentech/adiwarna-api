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
        return [
            'customer_id' => Customer::factory(),
            'date' => fake()->dateTimeBetween('-6 months', '+3 months'),
            'notes' => fake()->optional()->paragraph(),
        ];
    }
}
