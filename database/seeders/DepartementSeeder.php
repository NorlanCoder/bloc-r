<?php

namespace Database\Seeders;

use App\Models\Departement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departements = [
            ['code_dep' => 1, 'lib_dep' => 'ALIBORI'],
            ['code_dep' => 2, 'lib_dep' => 'ATACORA'],
            ['code_dep' => 3, 'lib_dep' => 'ATLANTIQ.'],
            ['code_dep' => 4, 'lib_dep' => 'BORGOU'],
            ['code_dep' => 5, 'lib_dep' => 'COLLINES'],
            ['code_dep' => 6, 'lib_dep' => 'COUFFO'],
            ['code_dep' => 7, 'lib_dep' => 'DONGA'],
            ['code_dep' => 8, 'lib_dep' => 'LITTORAL'],
            ['code_dep' => 9, 'lib_dep' => 'MONO'],
            ['code_dep' => 10, 'lib_dep' => 'OUEME'],
            ['code_dep' => 11, 'lib_dep' => 'PLATEAU'],
            ['code_dep' => 12, 'lib_dep' => 'ZOU'],
        ];

        foreach ($departements as $departement) {
            Departement::create($departement);
        }
    }
}
