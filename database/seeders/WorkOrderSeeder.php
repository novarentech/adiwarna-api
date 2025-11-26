<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\WorkOrder;
use Illuminate\Database\Seeder;

class WorkOrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::with('locations')->get();
        $employees = Employee::all();

        if ($customers->isEmpty() || $employees->isEmpty()) {
            $this->command->warn('No customers or employees found. Please run CustomerSeeder and EmployeeSeeder first.');
            return;
        }

        $customers->random(min(5, $customers->count()))->each(function ($customer) use ($employees) {
            WorkOrder::factory()
                ->count(rand(2, 3))
                ->for($customer)
                ->create()
                ->each(function ($workOrder) use ($customer, $employees) {
                    // Set customer location if available
                    if ($customer->locations->isNotEmpty()) {
                        $workOrder->update([
                            'customer_location_id' => $customer->locations->random()->id,
                        ]);
                    }

                    // Attach employees (position will be retrieved from employees table)
                    $selectedEmployees = $employees->random(rand(2, 4));
                    $workOrder->employees()->attach($selectedEmployees->pluck('id')->toArray());
                });
        });
    }
}
