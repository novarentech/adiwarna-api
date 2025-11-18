<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\PurchaseOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseOrder>
 */
class PurchaseOrderFactory extends Factory
{
    protected $model = PurchaseOrder::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'po_no' => 'PO-' . fake()->unique()->numberBetween(1000, 9999),
            'date' => fake()->dateTimeBetween('-1 year', 'now'),
            'subject' => fake()->sentence(),
            'discount' => fake()->randomFloat(2, 0, 15),
        ];
    }
}
