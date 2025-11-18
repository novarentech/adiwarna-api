<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\EquipmentProject;
use Illuminate\Database\Eloquent\Factories\Factory;

class EquipmentProjectFactory extends Factory
{
    protected $model = EquipmentProject::class;

    public function definition(): array
    {
        $equipmentTypes = ['Compressor', 'Generator', 'Welding Machine', 'Crane', 'Forklift', 'Excavator'];
        $conditions = ['Good', 'Fair', 'Need Maintenance', 'Under Repair'];

        return [
            'project_name' => fake()->catchPhrase() . ' Project',
            'customer_id' => Customer::factory(),
            'equipment_name' => fake()->randomElement($equipmentTypes) . ' ' . fake()->bothify('##??'),
            'equipment_type' => fake()->randomElement($equipmentTypes),
            'quantity' => fake()->numberBetween(1, 5),
            'condition' => fake()->randomElement($conditions),
            'assigned_date' => fake()->dateTimeBetween('-6 months', 'now'),
            'return_date' => fake()->optional()->dateTimeBetween('now', '+6 months'),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
