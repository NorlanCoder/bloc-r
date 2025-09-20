<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer le super admin par défaut
        User::create([
            'nom' => 'Super',
            'prenom' => 'Admin',
            'email' => 'superadmin@bloc-r.com',
            'password' => Hash::make('password123'),
            'telephone' => '1234567890',
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        // Créer un admin par défaut
        User::create([
            'nom' => 'Admin',
            'prenom' => 'Principal',
            'email' => 'admin@bloc-r.com',
            'password' => Hash::make('password123'),
            'telephone' => '1234567891',
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Créer un agent par défaut
        User::create([
            'nom' => 'Agent',
            'prenom' => 'Test',
            'email' => 'agent@bloc-r.com',
            'password' => Hash::make('password123'),
            'telephone' => '1234567892',
            'role' => 'agent',
            'is_active' => true,
        ]);
    }
}