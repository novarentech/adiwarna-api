<?php

namespace Database\Factories;

use App\Models\DocumentTransmittal;
use App\Models\TransmittalDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransmittalDocumentFactory extends Factory
{
    protected $model = TransmittalDocument::class;

    public function definition(): array
    {
        return [
            'transmittal_id' => DocumentTransmittal::factory(),
            'wo_number' => str_pad(fake()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'wo_year' => fake()->numberBetween(2020, 2025),
            'location' => fake()->city(),
        ];
    }
}
