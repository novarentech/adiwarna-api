<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerLocation;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create customers with locations
        Customer::factory()
            ->count(10)
            ->has(CustomerLocation::factory()->count(rand(1, 3)), 'locations')
            ->create();
    }
}
