<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Database\Seeder;

class PurchaseOrderSeeder extends Seeder
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

        // Create purchase orders with items
        $customers->random(5)->each(function ($customer) {
            PurchaseOrder::factory()
                ->count(rand(1, 2))
                ->for($customer)
                ->has(PurchaseOrderItem::factory()->count(rand(2, 5)), 'items')
                ->create();
        });
    }
}
