<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\CustomerLocation;
use App\Models\WorkAssignment;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkAssignmentFactory extends Factory
{
    protected $model = WorkAssignment::class;

    public function definition(): array
    {
        $customer = Customer::factory()->create();
        $date = fake()->dateTimeBetween('-3 months', 'now');
        $year = $date->format('Y');
        $assignmentNo = fake()->unique()->numberBetween(1000, 9999);

        return [
            'assignment_no' => 'WA-' . $assignmentNo,
            'assignment_year' => $year,
            'date' => $date,
            'ref_no' => 'REF-' . fake()->numberBetween(100, 999),
            'ref_year' => $year,
            'customer_id' => $customer->id,
            'customer_location_id' => CustomerLocation::factory()->create(['customer_id' => $customer->id])->id,
            'ref_po_no_instruction' => fake()->optional()->bothify('PO-####'),
            'scope' => fake()->paragraph(),
            'estimation' => fake()->optional()->randomElement(['1 week', '2 weeks', '1 month', '2 months']),
            'mobilization' => fake()->optional()->date(),
            'auth_name' => fake()->name(),
            'auth_pos' => fake()->jobTitle(),
        ];
    }
}
