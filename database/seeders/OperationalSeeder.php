<?php

namespace Database\Seeders;

use App\Models\Operational;
use Illuminate\Database\Seeder;

class OperationalSeeder extends Seeder
{
    public function run(): void
    {
        Operational::factory()->count(20)->create();
    }
}
