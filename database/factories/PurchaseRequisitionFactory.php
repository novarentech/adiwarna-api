<?php

namespace Database\Factories;

use App\Enums\PurchaseRequisitionRouting;
use App\Enums\PurchaseRequisitionStatus;
use App\Models\PurchaseRequisition;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseRequisitionFactory extends Factory
{
    protected $model = PurchaseRequisition::class;

    public function definition(): array
    {
        $year = fake()->year();
        $subTotal = fake()->randomFloat(2, 1000, 50000);
        $vatPercentage = 10;
        $vatAmount = $subTotal * ($vatPercentage / 100);
        $totalAmount = $subTotal + $vatAmount;

        return [
            'pr_no' => 'PR-' . fake()->unique()->numberBetween(1000, 9999),
            'rev_no' => fake()->optional()->numerify('REV-###'),
            'date' => fake()->dateTimeBetween($year . '-01-01', $year . '-12-31'),
            'required_delivery' => fake()->dateTimeBetween($year . '-01-01', $year . '-12-31'),
            'po_no_cash' => fake()->optional()->numerify('PO-####'),
            'supplier' => fake()->company(),
            'place_of_delivery' => fake()->address(),
            'routing' => fake()->randomElement(PurchaseRequisitionRouting::cases()),
            'sub_total' => $subTotal,
            'vat_percentage' => $vatPercentage,
            'vat_amount' => $vatAmount,
            'total_amount' => $totalAmount,
            'requested_by' => fake()->name(),
            'approved_by' => fake()->name(),
            'authorized_by' => fake()->name(),
            'status' => fake()->randomElement(PurchaseRequisitionStatus::cases()),
            'notes' => fake()->optional()->paragraph(),
        ];
    }
}
