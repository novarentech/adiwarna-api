<?php

namespace Database\Seeders;

use App\Models\About;
use Illuminate\Database\Seeder;

class AboutSeeder extends Seeder
{
    public function run(): void
    {
        // Create one active company info
        About::factory()->active()->create([
            'company_name' => 'PT Adiwarna Alam Raya',
            'email' => 'info@adiwarna.com',
        ]);

        // Create some inactive company info (historical)
        About::factory()->count(2)->create();
    }
}
