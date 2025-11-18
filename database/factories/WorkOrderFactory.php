<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\WorkOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkOrderFactory extends Factory
{
    protected $model = WorkOrder::class;

    public function definition(): array
    {
        $year = fake()->year();

        return [
            'work_order_no' => 'WO-' . fake()->unique()->numberBetween(1000, 9999),
            'work_order_year' => $year,
            'date' => fake()->dateTimeBetween($year . '-01-01', $year . '-12-31'),
            'customer_id' => Customer::factory(),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(['pending', 'in_progress', 'completed', 'cancelled']),
        ];
    }
}
