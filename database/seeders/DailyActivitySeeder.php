<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\DailyActivity;
use App\Models\DailyActivityDescription;
use App\Models\DailyActivityMember;
use App\Models\User;
use Illuminate\Database\Seeder;

class DailyActivitySeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $users = User::all();

        if ($customers->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No customers or users found. Please run CustomerSeeder and UserSeeder first.');
            return;
        }

        $customers->random(min(5, $customers->count()))->each(function ($customer) use ($users) {
            DailyActivity::factory()
                ->count(rand(2, 4))
                ->for($customer)
                ->create()
                ->each(function ($activity) use ($users) {
                    // Add members
                    for ($i = 0; $i < rand(2, 4); $i++) {
                        DailyActivityMember::create([
                            'daily_activity_id' => $activity->id,
                            'member_name' => $users->random()->name,
                        ]);
                    }

                    // Add descriptions
                    for ($i = 0; $i < rand(2, 5); $i++) {
                        DailyActivityDescription::create([
                            'daily_activity_id' => $activity->id,
                            'description' => fake()->paragraph(),
                        ]);
                    }
                });
        });
    }
}
