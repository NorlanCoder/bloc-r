<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Circonscription;
use App\Models\Departement;
use App\Models\Communes;
use App\Models\Militant;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SuperAdminSeeder::class,
            DepartementSeeder::class,
            CirconscriptionSeeder::class,
            CommuneSeeder::class,
        ]);
    }
}
