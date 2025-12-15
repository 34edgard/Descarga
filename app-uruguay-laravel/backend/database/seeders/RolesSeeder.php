<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = DB::table('roles')->get();
        if ($roles->isEmpty()) {
            DB::table('roles')->insert([
                ['name' => 'Administrador', 'guard_name' => 'web'],
                ['name' => 'Secretaria', 'guard_name' => 'web']
            ]);
        }
    }
}
