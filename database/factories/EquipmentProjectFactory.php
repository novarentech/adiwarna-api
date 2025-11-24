<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\CustomerLocation;
use App\Models\EquipmentProject;
use Illuminate\Database\Eloquent\Factories\Factory;

class EquipmentProjectFactory extends Factory
{
    protected $model = EquipmentProject::class;

    public function definition(): array
    {
        $preparedBy = ['Smith', 'James', 'Kivy', 'Lena', 'Chairul Anwar'];
        $verifiedBy = ['Van Gogh', 'Admin', 'Messi', 'Loki', 'Agung Pramudya'];

        return [
            'customer_id' => Customer::factory(),
            'customer_location_id' => CustomerLocation::factory(),
            'project_date' => fake()->dateTimeBetween('-6 months', 'now'),
            'prepared_by' => fake()->randomElement($preparedBy),
            'verified_by' => fake()->randomElement($verifiedBy),
        ];
    }
}
