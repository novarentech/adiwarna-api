<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Schedule;
use App\Models\ScheduleItem;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();

        if ($customers->isEmpty()) {
            $this->command->warn('No customers found. Please run CustomerSeeder first.');
            return;
        }

        $customers->random(min(5, $customers->count()))->each(function ($customer) {
            Schedule::factory()
                ->count(rand(1, 2))
                ->for($customer)
                ->create()
                ->each(function ($schedule) {
                    ScheduleItem::create([
                        'schedule_id' => $schedule->id,
                        'description' => fake()->sentence(),
                        'time' => fake()->time(),
                    ]);
                });
        });
    }
}
