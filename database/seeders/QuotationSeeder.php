<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Quotation;
use App\Models\QuotationAdiwarna;
use App\Models\QuotationClient;
use App\Models\QuotationItem;
use Illuminate\Database\Seeder;

class QuotationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::all();

        if ($customers->isEmpty()) {
            $this->command->warn('No customers found. Please run CustomerSeeder first.');
            return;
        }

        // Create quotations with items, adiwarnas, and clients
        $customers->random(5)->each(function ($customer) {
            Quotation::factory()
                ->count(rand(1, 3))
                ->for($customer)
                ->has(QuotationItem::factory()->count(rand(2, 5)), 'items')
                ->has(QuotationAdiwarna::factory()->count(rand(1, 3)), 'adiwarnas')
                ->has(QuotationClient::factory()->count(rand(1, 3)), 'clients')
                ->create();
        });
    }
}
