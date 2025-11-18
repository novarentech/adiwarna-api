<?php

namespace Database\Factories;

use App\Models\About;
use Illuminate\Database\Eloquent\Factories\Factory;

class AboutFactory extends Factory
{
    protected $model = About::class;

    public function definition(): array
    {
        return [
            'company_name' => fake()->company(),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->companyEmail(),
            'description' => fake()->paragraph(),
            'vision' => fake()->sentence(),
            'mission' => fake()->paragraph(),
            'is_active' => false,
        ];
    }

    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => true,
        ]);
    }
}
