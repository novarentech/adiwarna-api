<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\CustomerLocation;
use App\Models\WorkAssignment;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkAssignmentFactory extends Factory
{
    protected $model = WorkAssignment::class;

    public function definition(): array
    {
        $customer = Customer::factory()->create();

        return [
            'customer_id' => $customer->id,
            'customer_location_id' => CustomerLocation::factory()->create(['customer_id' => $customer->id])->id,
            'date' => fake()->dateTimeBetween('-3 months', 'now'),
            'description' => fake()->paragraph(),
        ];
    }
}
