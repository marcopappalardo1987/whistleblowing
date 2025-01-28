<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ChangeDefaultUserDetails extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creare o aggiornare un utente con nome, email e password
        User::updateOrCreate(
            ['email' => 'test@example.com'], // Identifica l'utente con questa email
            [
                'name' => 'Marco Pappalardo',
                'email' => 'info@advisionplus.com', // Nuova email
                'password' => Hash::make('Catania2020.*@'), // Nuova password
            ]
        );
    }
}
