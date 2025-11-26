<?php

namespace Database\Factories;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseOrderItem>
 */
class PurchaseOrderItemFactory extends Factory
{
    protected $model = PurchaseOrderItem::class;

    public function definition(): array
    {
        return [
            'purchase_order_id' => PurchaseOrder::factory(),
            'description' => fake()->sentence(),
            'quantity' => fake()->numberBetween(1, 100),
            'unit' => fake()->randomElement(['pcs', 'unit', 'set', 'lot', 'meter']),
            'rate' => fake()->randomFloat(2, 100, 10000),
        ];
    }
}
