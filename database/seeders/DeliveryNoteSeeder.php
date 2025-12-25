<?php

namespace Database\Seeders;

use App\Models\DeliveryNote;
use App\Models\DeliveryNoteItem;
use Illuminate\Database\Seeder;

class DeliveryNoteSeeder extends Seeder
{
    public function run(): void
    {
        DeliveryNote::factory()
            ->count(10)
            ->create()
            ->each(function ($deliveryNote) {
                for ($i = 0; $i < rand(2, 8); $i++) {
                    DeliveryNoteItem::create([
                        'delivery_note_id' => $deliveryNote->id,
                        'item_name' => fake()->randomElement([
                            'Steel Pipe 2 inch',
                            'Welding Equipment',
                            'Safety Helmet',
                            'Work Gloves',
                            'Fire Extinguisher',
                            'Measuring Tape',
                            'Drill Machine',
                            'Angle Grinder'
                        ]),
                        'serial_number' => fake()->optional()->bothify('S/N: ###-??-####'),
                        'qty' => fake()->numberBetween(1, 10),
                    ]);
                }
            });
    }
}
