<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\EquipmentGeneral;
use App\Models\EquipmentProject;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        // Create general equipment
        EquipmentGeneral::factory()->count(15)->create();

        // Create project equipment
        $customers = Customer::all();

        if ($customers->isEmpty()) {
            $this->command->warn('No customers found. Skipping project equipment seeding.');
            return;
        }

        $customers->random(min(5, $customers->count()))->each(function ($customer) {
            EquipmentProject::factory()
                ->count(rand(1, 3))
                ->for($customer)
                ->create();
        });
    }
}
