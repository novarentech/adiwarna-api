<?php

namespace Database\Factories;

use App\Models\Operational;
use Illuminate\Database\Eloquent\Factories\Factory;

class OperationalFactory extends Factory
{
    protected $model = Operational::class;

    public function definition(): array
    {
        $types = ['Income', 'Expense', 'Investment', 'Other'];

        return [
            'date' => fake()->dateTimeBetween('-1 year', 'now'),
            'type' => fake()->randomElement($types),
            'description' => fake()->sentence(),
            'amount' => fake()->randomFloat(2, 100, 50000),
            'notes' => fake()->optional()->paragraph(),
        ];
    }
}
