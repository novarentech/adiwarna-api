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
                    $qty = fake()->numberBetween(1, 100);
                    $unitPrice = fake()->randomFloat(2, 100, 10000);

                    PurchaseRequisitionItem::create([
                        'purchase_requisition_id' => $pr->id,
                        'qty' => $qty,
                        'unit' => fake()->randomElement(['pcs', 'unit', 'set', 'lot']),
                        'description' => fake()->sentence(),
                        'unit_price' => $unitPrice,
                        'total_price' => $qty * $unitPrice,
                    ]);
                }
            });
    }
}
