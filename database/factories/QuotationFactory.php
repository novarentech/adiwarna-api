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
        $date = fake()->dateTimeBetween('-1 year', 'now');

        return [
            'customer_id' => Customer::factory(),
            'date' => $date,
            'ref_no' => 'QT-' . fake()->unique()->numberBetween(1000, 9999),
            'ref_year' => $date->format('Y'),
            'pic_name' => fake()->name(),
            'pic_phone' => fake()->numerify('08##########'),
            'subject' => fake()->sentence(),
            'top' => fake()->randomElement(['Net 30', 'Net 45', 'Net 60', 'COD', 'CBD']),
            'valid_until' => fake()->randomElement(['30 days', '45 days', '60 days', '90 days']),
            'clause' => fake()->optional()->paragraph(),
            'workday' => fake()->optional()->randomElement(['5 days', '10 days', '15 days', '30 days']),
            'auth_name' => fake()->name(),
            'auth_position' => fake()->randomElement(['Director', 'Manager', 'Supervisor', 'Sales Manager']),
            'discount' => fake()->randomFloat(2, 0, 15),
        ];
    }
}
