<?php

namespace Database\Seeders;

use App\Models\Relationship;
use Illuminate\Database\Seeder;

class RelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Relationships
        $relationships = [
            'Madre',
            'Padre',
            'Hermano',
            'Hermana',
            'Hijo',
            'Hija',
            'Esposa',
            'Marido',
            'Otro',
        ];
        foreach ($relationships as $relationship) {
            Relationship::create([
                'name' => $relationship,
            ]);
        }
    }
}
