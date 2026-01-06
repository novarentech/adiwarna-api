<?php

namespace Database\Factories;

use App\Enums\PurchaseRequisitionRouting;
use App\Enums\PurchaseRequisitionStatus;
use App\Enums\PurchaseRequisitionSupplier;
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

        // Ensure supplier and routing match
        $routingValue = fake()->randomElement(['online', 'offline']);

        return [
            'pr_no' => 'PR-' . fake()->unique()->numberBetween(1000, 9999),
            'date' => fake()->dateTimeBetween($year . '-01-01', $year . '-12-31'),
            'po_no_cash' => fake()->optional()->numerify('PO-####'),
            'supplier' => $routingValue, // Same as routing
            'routing' => $routingValue,
            'sub_total' => $subTotal,
            'vat_percentage' => $vatPercentage,
            'vat_amount' => $vatAmount,
            'total_amount' => $totalAmount,
            'requested_by' => fake()->name(),
            'requested_position' => fake()->jobTitle(),
            'approved_by' => fake()->name(),
            'approved_position' => fake()->jobTitle(),
            'authorized_by' => fake()->name(),
            'status' => fake()->randomElement(PurchaseRequisitionStatus::cases()),
        ];
    }
}
