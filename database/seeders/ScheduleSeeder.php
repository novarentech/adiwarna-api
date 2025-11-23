<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Schedule;
use App\Models\ScheduleWorkOrder;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing customers or create some
        $customers = Customer::limit(5)->get();

        if ($customers->isEmpty()) {
            $customers = Customer::factory(5)->create();
        }

        // Create 15 schedules with work orders
        foreach ($customers as $customer) {
            Schedule::factory(3)
                ->for($customer)
                ->create()
                ->each(function (Schedule $schedule) {
                    // Create 1-5 work orders for each schedule
                    ScheduleWorkOrder::factory(rand(1, 5))
                        ->for($schedule)
                        ->create();
                });
        }
    }
}
