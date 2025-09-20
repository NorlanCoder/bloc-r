<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed essential data first
        User::create([
            'nom' => fake()->firstName(),
            'prenom' => fake()->lastName(),
            'role' => 'admin_sup',
            'email' => fake()->email(),
            'password' => Hash::make('12345678')
        ]);
        User::create([
            'nom' => fake()->firstName(),
            'prenom' => fake()->lastName(),
            'role' => 'admin',
            'email' => fake()->email(),
            'password' => Hash::make('12345678')
        ]);
        User::create([
            'nom' => fake()->firstName(),
            'prenom' => fake()->lastName(),
            'role' => 'user',
            'email' => fake()->email(),
            'password' => Hash::make('12345678')
        ]);
        $this->call([
            DepartementSeeder::class,
            CirconscriptionSeeder::class,
            CommuneSeeder::class,
            PrixSeeder::class,
        ]);

    }
}
