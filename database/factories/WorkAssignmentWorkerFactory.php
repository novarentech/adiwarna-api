<?php

namespace Database\Factories;

use App\Models\WorkAssignment;
use App\Models\WorkAssignmentWorker;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkAssignmentWorkerFactory extends Factory
{
    protected $model = WorkAssignmentWorker::class;

    public function definition(): array
    {
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

        return [
            'work_assignment_id' => WorkAssignment::factory(),
            'worker_name' => fake()->name(),
            'position' => fake()->randomElement($positions),
        ];
    }
}
