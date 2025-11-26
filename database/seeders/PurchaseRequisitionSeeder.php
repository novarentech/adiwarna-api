<?php

namespace Database\Seeders;

use App\Models\PurchaseRequisition;
use App\Models\PurchaseRequisitionItem;
use Illuminate\Database\Seeder;

class PurchaseRequisitionSeeder extends Seeder
{
    public function run(): void
    {
        PurchaseRequisition::factory()
            ->count(10)
            ->create()
            ->each(function ($pr) {
                for ($i = 0; $i < rand(2, 5); $i++) {
                    PurchaseRequisitionItem::create([
                        'purchase_requisition_id' => $pr->id,
                        'qty' => fake()->numberBetween(1, 100),
                        'unit' => fake()->randomElement(['pcs', 'unit', 'set', 'lot']),
                        'description' => fake()->sentence(),
                        'unit_price' => fake()->randomFloat(2, 100, 10000),
                    ]);
                }
            });
    }
}
