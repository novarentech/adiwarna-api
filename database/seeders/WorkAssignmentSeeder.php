<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\WorkAssignment;
use Illuminate\Database\Seeder;

class WorkAssignmentSeeder extends Seeder
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
            WorkAssignment::factory()
                ->count(rand(1, 2))
                ->create([
                    'customer_id' => $customer->id,
                    'customer_location_id' => $customer->locations->first()?->id,
                ])
                ->each(function ($workAssignment) use ($employees) {
                    $selectedEmployees = $employees->random(rand(2, 4));
                    foreach ($selectedEmployees as $employee) {
                        $workAssignment->employees()->attach($employee->id, [
                            'detail' => fake()->sentence(),
                        ]);
                    }
                });
        });
    }
}
