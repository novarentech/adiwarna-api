<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_no' => 'C' . fake()->unique()->numberBetween(1000, 9999),
            'name' => fake()->company(),
            'phone_number' => fake()->phoneNumber(),
            'address' => fake()->address(),
        ];
    }
}
