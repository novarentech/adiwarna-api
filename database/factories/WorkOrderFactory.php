<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\WorkOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkOrderFactory extends Factory
{
    protected $model = WorkOrder::class;

    public function definition(): array
    {
        $year = fake()->numberBetween(2024, 2025);
        $scopeOptions = [
            'MPI',
            'Penetrant Test',
            'UT Wall Thickness Spot Check',
            'Load Test',
            'Lifting Gear Inspection',
            'Tracking Iron Inspection',
            'Hydrotest/Pressure Testing',
            'Offshore Contained Inspection',
            'PPV Testing',
            'Visual Color Code',
            'Witness Leak Test',
            'Blig and Shackle Inspection',
            'Rigging Inspection',
            'Sprinkler Bar Inspection',
            'Other',
        ];

        return [
            'work_order_no' => str_pad(fake()->unique()->numberBetween(1, 999), 3, '0', STR_PAD_LEFT),
            'work_order_year' => $year,
            'date' => fake()->dateTimeBetween($year . '-01-01', $year . '-12-31'),
            'customer_id' => Customer::factory(),
            'customer_location_id' => null, // Will be set in seeder
            'scope_of_work' => fake()->randomElements($scopeOptions, rand(2, 5)),
        ];
    }
}
