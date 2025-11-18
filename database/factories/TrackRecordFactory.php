<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\TrackRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrackRecordFactory extends Factory
{
    protected $model = TrackRecord::class;

    public function definition(): array
    {
        $statuses = ['Planning', 'In Progress', 'On Hold', 'Completed', 'Cancelled'];

        return [
            'project_name' => fake()->catchPhrase() . ' Project',
            'customer_id' => Customer::factory(),
            'date' => fake()->dateTimeBetween('-1 year', 'now'),
            'status' => fake()->randomElement($statuses),
            'description' => fake()->paragraph(),
            'milestones' => [
                fake()->sentence(),
                fake()->sentence(),
                fake()->sentence(),
            ],
        ];
    }
}
