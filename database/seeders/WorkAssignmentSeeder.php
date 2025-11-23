<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\WorkAssignment;
use Illuminate\Database\Seeder;

class WorkAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();

        if ($customers->isEmpty()) {
            $this->command->warn('No customers found. Please run CustomerSeeder first.');
            return;
        }

        $positions = [
            'Team Leader',
            'Technician',
            'Senior Technician',
            'Safety Officer',
            'Quality Control',
            'Supervisor',
            'Foreman',
            'Assistant',
            'Engineer',
            'Site Manager',
        ];

        $workerNames = [
            'Budi Santoso',
            'Ahmad Yani',
            'Siti Rahayu',
            'Dewi Lestari',
            'Eko Prasetyo',
            'Rina Wijaya',
            'Agus Setiawan',
            'Maya Sari',
            'Dedi Kurniawan',
            'Fitri Handayani',
            'Hendra Gunawan',
            'Lina Marlina',
            'Rudi Hartono',
            'Sri Wahyuni',
            'Bambang Suryanto',
        ];

        $customers->random(min(5, $customers->count()))->each(function ($customer) use ($positions, $workerNames) {
            WorkAssignment::factory()
                ->count(rand(1, 2))
                ->create([
                    'customer_id' => $customer->id,
                    'customer_location_id' => $customer->locations->first()?->id,
                ])
                ->each(function ($workAssignment) use ($positions, $workerNames) {
                    $numberOfWorkers = rand(2, 5);
                    $selectedWorkers = collect($workerNames)->random($numberOfWorkers);

                    foreach ($selectedWorkers as $workerName) {
                        $workAssignment->workers()->create([
                            'worker_name' => $workerName,
                            'position' => fake()->randomElement($positions),
                        ]);
                    }
                });
        });
    }
}
