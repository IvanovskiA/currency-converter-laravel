<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Conversion;

class ConversionSeeder extends Seeder
{
    public function run(): void
    {
        Conversion::factory()->count(10)->create();
    }
}
