<?php

namespace Database\Seeders;

use App\Models\Chauffeur;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create(['name' => 'Admin', 'email' => 'admin@test.com',
                      'password' => Hash::make('password'), 'role' => 'admin']);

        foreach (['Karim', 'Sara', 'Youssef'] as $nom) {
            $u = User::create(['name' => $nom, 'email' => strtolower($nom) . '@test.com',
                               'password' => Hash::make('password'), 'role' => 'chauffeur']);
            Chauffeur::create(['user_id' => $u->id, 'nom' => $nom]);
        }

        $u = User::create(['name' => 'Client Demo', 'email' => 'client@test.com',
                           'password' => Hash::make('password'), 'role' => 'client']);
        Client::create(['user_id' => $u->id, 'nom' => 'Client Demo', 'telephone' => '0600000000']);

        foreach (['Societe Atlas', 'Cafe Marrakech', 'Pharmacie Nour'] as $i => $nom) {
            Client::create(['nom' => $nom, 'telephone' => '06000000' . $i . $i]);
        }
    }
}
