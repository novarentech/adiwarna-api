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
        return [
            'customer_id' => Customer::factory(),
            'customer_name' => fake()->company(),
            'customer_address' => fake()->address(),
            'pic_name' => fake()->name(),
            'pic_phone' => fake()->phoneNumber(),
            'date' => fake()->dateTimeBetween('-6 months', 'now'),
            'description' => fake()->optional()->paragraph(),
        ];
    }
}
