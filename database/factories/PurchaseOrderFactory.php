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
        $date = fake()->dateTimeBetween('-1 year', 'now');
        $requiredDate = fake()->dateTimeBetween($date, '+3 months');

        return [
            'customer_id' => Customer::factory(),
            'po_no' => 'PO-' . fake()->unique()->numberBetween(1000, 9999),
            'po_year' => $date->format('Y'),
            'date' => $date,
            'pic_name' => fake()->name(),
            'pic_phone' => fake()->numerify('08##########'),
            'required_date' => $requiredDate,
            'top_dp' => fake()->optional()->randomElement(['30%', '40%', '50%']),
            'top_cod' => fake()->optional()->randomElement(['COD', 'CBD']),
            'quotation_ref' => fake()->optional()->numerify('QT-####'),
            'purchase_requisition_no' => fake()->optional()->numerify('PR-####'),
            'purchase_requisition_year' => fake()->optional()->year(),
            'discount' => fake()->randomFloat(2, 0, 15),
            'req_name' => fake()->optional()->name(),
            'req_pos' => fake()->optional()->jobTitle(),
            'app_name' => fake()->optional()->name(),
            'app_pos' => fake()->optional()->jobTitle(),
            'auth_name' => fake()->name(),
            'auth_pos' => fake()->randomElement(['Director', 'Manager', 'Supervisor']),
            'isTax' => fake()->boolean()
        ];
    }
}
