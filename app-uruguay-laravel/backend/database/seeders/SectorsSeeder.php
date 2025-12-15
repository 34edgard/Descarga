<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = base_path('database/data/sector.json');
        $data = json_decode(file_get_contents($jsonPath), true);

        if (is_array($data)) {
            $insertData = [];
            foreach ($data['data'] as $item) {
                // Selecciona solo los campos necesarios
                $insertData[] = [
                    'parish_id' => $item['id_parroquia'] ?? null,
                    'name' => $item['nombre_sector'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            if (!empty($insertData)) {
                DB::table('sectors')->insert($insertData);
            }
        }
    }
}
