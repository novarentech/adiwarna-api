<?php

namespace Database\Seeders;

use App\Models\MaterialReceivingReport;
use App\Models\MaterialReceivingReportItem;
use Illuminate\Database\Seeder;

class MaterialReceivingReportSeeder extends Seeder
{
    public function run(): void
    {
        MaterialReceivingReport::factory()
            ->count(10)
            ->create()
            ->each(function ($mrr) {
                for ($i = 0; $i < rand(2, 5); $i++) {
                    MaterialReceivingReportItem::create([
                        'material_receiving_report_id' => $mrr->id,
                        'description' => fake()->sentence(),
                        'order_qty' => fake()->numberBetween(10, 100),
                        'received_qty' => fake()->numberBetween(10, 100),
                        'remarks' => fake()->optional()->sentence(),
                    ]);
                }
            });
    }
}
