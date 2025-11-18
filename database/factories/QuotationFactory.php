<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Quotation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quotation>
 */
class QuotationFactory extends Factory
{
    protected $model = Quotation::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'ref_no' => 'QT-' . fake()->unique()->numberBetween(1000, 9999),
            'date' => fake()->dateTimeBetween('-1 year', 'now'),
            'subject' => fake()->sentence(),
            'discount' => fake()->randomFloat(2, 0, 15),
        ];
    }
}
