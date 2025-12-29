<?php

namespace Database\Factories;

use App\Enums\DeliveryNoteStatus;
use App\Models\DeliveryNote;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeliveryNoteFactory extends Factory
{
    protected $model = DeliveryNote::class;

    public function definition(): array
    {
        $year = fake()->year();

        return [
            'delivery_note_no' => 'SJ' . fake()->unique()->numerify('###') . '/AWP/XII/' . $year,
            'date' => fake()->dateTimeBetween($year . '-01-01', $year . '-12-31'),
            'customer' => fake()->company(),
            'customer_address' => fake()->address(),
            'wo_no' => 'WO/' . $year . '/' . fake()->numberBetween(100, 999),
            'delivered_with' => fake()->optional()->randomElement(['Truck', 'Van', 'Motorcycle']),
            'vehicle_plate' => 'B ' . fake()->numberBetween(1000, 9999) . ' ' . fake()->randomLetter() . fake()->randomLetter() . fake()->randomLetter(),
            'delivered_by' => fake()->name(),
            'received_by' => fake()->optional()->name(),
            'status' => fake()->randomElement(DeliveryNoteStatus::cases()),
            'notes' => fake()->optional()->paragraph(),
        ];
    }
}
