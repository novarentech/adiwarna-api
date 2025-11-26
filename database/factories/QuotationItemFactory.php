<?php

namespace Database\Factories;

use App\Models\Quotation;
use App\Models\QuotationItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuotationItem>
 */
class QuotationItemFactory extends Factory
{
    protected $model = QuotationItem::class;

    public function definition(): array
    {
        return [
            'quotation_id' => Quotation::factory(),
            'description' => fake()->sentence(),
            'quantity' => fake()->numberBetween(1, 100),
            'unit' => fake()->randomElement(['pcs', 'unit', 'set', 'lot', 'meter']),
            'rate' => fake()->randomFloat(2, 100, 10000),
        ];
    }
}
