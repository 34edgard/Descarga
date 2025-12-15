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
        // Call other seeders
        $this->call([
            StatesSeeder::class,
            MunicipalitiesSeeder::class,
            ParishesSeeder::class,
            SectorsSeeder::class,
            NutritionalStatusesSeeder::class,
            DisabilitiesSeeder::class,
            ProvenancesSeeder::class,
            RelationshipSeeder::class,
            NationalitiesSeeder::class,
            MedicalConditionsSeeder::class,
            EducationLevelsSeeder::class,
            OccupationsSeeder::class,

            RolesSeeder::class,
            UsersSeeder::class,
        ]);
    }
}
