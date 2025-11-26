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

        $reportTypes = ['Monthly Report', 'Quarterly Report', 'Annual Report', 'Project Report', 'Inspection Report'];
        $locations = ['Jakarta', 'Bandung', 'Surabaya', 'Semarang', 'Yogyakarta', 'Medan', 'Makassar'];

        foreach ($customers->random(min(5, $customers->count())) as $customer) {
            $transmittal = DocumentTransmittal::create([
                'name' => 'Transmittal for ' . $customer->name,
                'ta_no' => $this->generateTANumber(),
                'date' => now()->subDays(rand(1, 30)),
                'customer_id' => $customer->id,
                'customer_district' => fake()->optional()->city(),
                'pic_name' => fake()->name(),
                'report_type' => fake()->randomElement($reportTypes),
            ]);

            // Create 2-4 documents for each transmittal
            $documentCount = rand(2, 4);
            for ($i = 0; $i < $documentCount; $i++) {
                TransmittalDocument::create([
                    'transmittal_id' => $transmittal->id,
                    'wo_number' => str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                    'wo_year' => rand(2023, 2025),
                    'location' => fake()->randomElement($locations),
                ]);
            }
        }

        $this->command->info('Document transmittals seeded successfully.');
    }

    private function generateTANumber(): string
    {
        $months = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $year = date('Y');
        $month = $months[date('n') - 1];
        $number = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);

        return "{$number}/{$month}/{$year}";
    }
}
