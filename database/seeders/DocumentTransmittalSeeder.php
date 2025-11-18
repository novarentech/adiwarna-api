<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\DocumentTransmittal;
use App\Models\TransmittalDocument;
use Illuminate\Database\Seeder;

class DocumentTransmittalSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();

        if ($customers->isEmpty()) {
            $this->command->warn('No customers found. Please run CustomerSeeder first.');
            return;
        }

        $customers->random(min(5, $customers->count()))->each(function ($customer) {
            DocumentTransmittal::factory()
                ->count(rand(1, 2))
                ->for($customer)
                ->create()
                ->each(function ($transmittal) {
                    for ($i = 0; $i < rand(2, 5); $i++) {
                        TransmittalDocument::create([
                            'transmittal_id' => $transmittal->id,
                            'work_reference' => 'WR-' . fake()->numberBetween(1000, 9999),
                            'document_no' => 'DOC-' . fake()->numberBetween(1000, 9999),
                            'document_year' => fake()->year(),
                        ]);
                    }
                });
        });
    }
}
