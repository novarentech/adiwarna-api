<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('Starting database seeding...');

        // Seed in proper order to maintain referential integrity
        $this->call([
            // 1. Users (independent)
            UserSeeder::class,

            // 2. Master Data (independent)
            CustomerSeeder::class,
            EmployeeSeeder::class,

            // 3. Company Information (independent)
            AboutSeeder::class,

            // 4. Sales Modules (depends on customers)
            QuotationSeeder::class,
            PurchaseOrderSeeder::class,

            // 5. Operations Modules (depends on customers and employees)
            WorkAssignmentSeeder::class,
            DailyActivitySeeder::class,
            ScheduleSeeder::class,
            WorkOrderSeeder::class,

            // 6. Document Modules (depends on customers)
            DocumentTransmittalSeeder::class,
            PurchaseRequisitionSeeder::class,
            MaterialReceivingReportSeeder::class,

            // 7. Equipment & System Modules
            EquipmentSeeder::class,
            TrackRecordSeeder::class,
            OperationalSeeder::class,
        ]);

        $this->command->info('Database seeding completed successfully!');
    }
}
