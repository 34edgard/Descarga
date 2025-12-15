<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NutritionalStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = base_path('database/data/estado_nutricional.json');
        $data = json_decode(file_get_contents($jsonPath), true);

        if (is_array($data)) {
            $insertData = [];
            foreach ($data['data'] as $item) {
                // Selecciona solo los campos necesarios
                $insertData[] = [
                    'name' => $item['nombre_estado_nutricional'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            if (!empty($insertData)) {
                DB::table('nutritional_statuses')->insert($insertData);
            }
        }
    }
}
