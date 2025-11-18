<?php

namespace Database\Factories;

use App\Models\PurchaseRequisition;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseRequisitionFactory extends Factory
{
    protected $model = PurchaseRequisition::class;

    public function definition(): array
    {
        $year = fake()->year();

        return [
            'pr_no' => 'PR-' . fake()->unique()->numberBetween(1000, 9999),
            'pr_year' => $year,
            'date' => fake()->dateTimeBetween($year . '-01-01', $year . '-12-31'),
            'supplier' => fake()->company(),
            'delivery_place' => fake()->address(),
            'discount' => fake()->randomFloat(2, 0, 15),
            'notes' => fake()->optional()->paragraph(),
            'total_amount' => fake()->randomFloat(2, 1000, 100000),
        ];
    }
}
