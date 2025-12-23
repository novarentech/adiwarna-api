<?php

namespace Database\Factories;

use App\Enums\MaterialReceivingReportOrderBy;
use App\Enums\MaterialReceivingReportStatus;
use App\Models\MaterialReceivingReport;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaterialReceivingReportFactory extends Factory
{
    protected $model = MaterialReceivingReport::class;

    public function definition(): array
    {
        $year = fake()->year();

        return [
            'po_inv_pr_no' => fake()->randomElement(['PO', 'PR', 'INV']) . '-' . fake()->numberBetween(1000, 9999),
            'supplier' => fake()->company(),
            'receiving_date' => fake()->dateTimeBetween($year . '-01-01', $year . '-12-31'),
            'order_by' => fake()->randomElement(MaterialReceivingReportOrderBy::cases()),
            'received_by' => fake()->name(),
            'acknowledge_by' => fake()->name(),
            'status' => fake()->randomElement(MaterialReceivingReportStatus::cases()),
            'notes' => fake()->optional()->paragraph(),
        ];
    }
}
