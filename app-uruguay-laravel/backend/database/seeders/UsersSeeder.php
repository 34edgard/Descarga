<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'last_name' => 'Ashley',
            'first_name' => 'Test',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $user->assignRole('Administrador');
    }
}
