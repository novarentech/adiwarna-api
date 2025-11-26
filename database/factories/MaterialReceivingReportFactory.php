<?php

namespace Database\Factories;

use App\Models\MaterialReceivingReport;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaterialReceivingReportFactory extends Factory
{
    protected $model = MaterialReceivingReport::class;

    public function definition(): array
    {
        $year = fake()->year();

        return [
            'rr_no' => 'RR-' . fake()->unique()->numberBetween(1000, 9999),
            'rr_year' => $year,
            'date' => fake()->dateTimeBetween($year . '-01-01', $year . '-12-31'),
            'ref_pr_no' => 'PR-' . fake()->numberBetween(1000, 9999),
            'ref_po_no' => 'PO-' . fake()->numberBetween(1000, 9999),
            'supplier' => fake()->company(),
            'receiving_date' => fake()->dateTimeBetween($year . '-01-01', $year . '-12-31'),
            'notes' => fake()->optional()->paragraph(),
        ];
    }
}
