<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\CustomerLocation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerLocation>
 */
class CustomerLocationFactory extends Factory
{
    protected $model = CustomerLocation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'location' => fake()->city() . ' Office - ' . fake()->streetAddress(),
        ];
    }
}
