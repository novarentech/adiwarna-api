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
        $customers = Customer::all();
        $employees = Employee::all();

        if ($customers->isEmpty() || $employees->isEmpty()) {
            $this->command->warn('No customers or employees found. Please run CustomerSeeder and EmployeeSeeder first.');
            return;
        }

        $customers->random(min(5, $customers->count()))->each(function ($customer) use ($employees) {
            WorkOrder::factory()
                ->count(rand(1, 2))
                ->for($customer)
                ->create()
                ->each(function ($workOrder) use ($employees) {
                    $selectedEmployees = $employees->random(rand(2, 4));
                    foreach ($selectedEmployees as $employee) {
                        $workOrder->employees()->attach($employee->id, [
                            'detail' => fake()->sentence(),
                        ]);
                    }
                });
        });
    }
}
