<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\DailyActivity;
use App\Models\DailyActivityDescription;
use App\Models\DailyActivityMember;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class DailyActivitySeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $employees = Employee::all();

        if ($customers->isEmpty() || $employees->isEmpty()) {
            $this->command->warn('No customers or employees found. Please run CustomerSeeder and EmployeeSeeder first.');
            return;
        }

        $equipmentNumbers = [
            'EQ-001',
            'EQ-002',
            'EQ-003',
            'EQ-004',
            'EQ-005',
            'EQ-006',
            'EQ-007',
            'EQ-008',
            'EQ-009',
            'EQ-010',
        ];

        $workDescriptions = [
            'Installation of fire alarm control panel',
            'Testing smoke detectors',
            'Maintenance of fire extinguishers',
            'Inspection of emergency lighting system',
            'Calibration of fire detection sensors',
            'Replacement of fire hose reels',
            'Testing of sprinkler system',
            'Installation of manual call points',
            'Maintenance of fire alarm bells',
            'Inspection of fire safety equipment',
        ];

        $customers->random(min(5, $customers->count()))->each(function ($customer) use ($employees, $equipmentNumbers, $workDescriptions) {
            DailyActivity::factory()
                ->count(rand(2, 4))
                ->for($customer)
                ->create()
                ->each(function ($activity) use ($employees, $equipmentNumbers, $workDescriptions) {
                    // Add members (employee IDs)
                    $selectedEmployees = $employees->random(rand(2, 5));
                    foreach ($selectedEmployees as $employee) {
                        DailyActivityMember::create([
                            'daily_activity_id' => $activity->id,
                            'employee_id' => $employee->id,
                        ]);
                    }

                    // Add descriptions with equipment numbers
                    $numberOfDescriptions = rand(2, 5);
                    $selectedDescriptions = collect($workDescriptions)->random($numberOfDescriptions);

                    foreach ($selectedDescriptions as $description) {
                        DailyActivityDescription::create([
                            'daily_activity_id' => $activity->id,
                            'description' => $description,
                            'equipment_no' => fake()->randomElement($equipmentNumbers),
                        ]);
                    }
                });
        });
    }
}
