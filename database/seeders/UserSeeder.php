<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@adiwarna.com',
            'phone' => '081234567890',
        ]);

        // Create teknisi users
        User::factory()->teknisi()->create([
            'name' => 'Teknisi 1',
            'email' => 'teknisi1@adiwarna.com',
            'phone' => '081234567891',
        ]);

        User::factory()->teknisi()->create([
            'name' => 'Teknisi 2',
            'email' => 'teknisi2@adiwarna.com',
            'phone' => '081234567892',
        ]);

        // Create additional teknisi users
        User::factory()->teknisi()->count(3)->create();
    }
}
