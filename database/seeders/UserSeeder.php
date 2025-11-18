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
        ]);

        // Create teknisi users
        User::factory()->teknisi()->create([
            'name' => 'Teknisi 1',
            'email' => 'teknisi1@adiwarna.com',
        ]);

        User::factory()->teknisi()->create([
            'name' => 'Teknisi 2',
            'email' => 'teknisi2@adiwarna.com',
        ]);

        // Create regular users
        User::factory()->user()->count(3)->create();
    }
}
