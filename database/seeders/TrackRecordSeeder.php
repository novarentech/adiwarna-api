<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\TrackRecord;
use Illuminate\Database\Seeder;

class TrackRecordSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();

        if ($customers->isEmpty()) {
            $this->command->warn('No customers found. Please run CustomerSeeder first.');
            return;
        }

        $customers->random(min(5, $customers->count()))->each(function ($customer) {
            TrackRecord::factory()
                ->count(rand(2, 4))
                ->for($customer)
                ->create();
        });
    }
}
