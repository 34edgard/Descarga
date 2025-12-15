<?php

namespace Database\Seeders;

use App\Models\Nationality;
use Illuminate\Database\Seeder;

class NationalitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Nationality::create(['name' => 'Venezolano']);
        Nationality::create(['name' => 'Extranjero']);
    }
}
