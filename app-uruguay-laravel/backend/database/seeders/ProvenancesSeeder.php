<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvenancesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = base_path('database/data/procedencia.json');
        $data = json_decode(file_get_contents($jsonPath), true);

        if (is_array($data)) {
            $insertData = [];
            foreach ($data['data'] as $item) {
                // Selecciona solo los campos necesarios
                $insertData[] = [
                    'name' => $item['nombre_procedencia'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            if (!empty($insertData)) {
                DB::table('provenances')->insert($insertData);
            }
        }
    }
}
