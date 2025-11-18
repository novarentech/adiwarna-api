<?php

namespace Database\Factories;

use App\Models\EquipmentGeneral;
use Illuminate\Database\Eloquent\Factories\Factory;

class EquipmentGeneralFactory extends Factory
{
    protected $model = EquipmentGeneral::class;

    public function definition(): array
    {
        $equipmentTypes = ['Compressor', 'Generator', 'Welding Machine', 'Crane', 'Forklift', 'Excavator'];
        $conditions = ['Good', 'Fair', 'Need Maintenance', 'Under Repair'];

        return [
            'equipment_name' => fake()->randomElement($equipmentTypes) . ' ' . fake()->bothify('##??'),
            'equipment_type' => fake()->randomElement($equipmentTypes),
            'quantity' => fake()->numberBetween(1, 10),
            'condition' => fake()->randomElement($conditions),
            'specifications' => fake()->optional()->paragraph(),
            'purchase_date' => fake()->optional()->dateTimeBetween('-5 years', 'now'),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
