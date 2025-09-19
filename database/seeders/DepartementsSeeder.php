<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departements')->insert([
            ['code_departement' => 1, 'departement_label' => 'ALIBORI'],
            ['code_departement' => 2, 'departement_label' => 'ATACORA'],
            ['code_departement' => 3, 'departement_label' => 'ATLANTIQ.'],
            ['code_departement' => 4, 'departement_label' => 'BORGOU'],
            ['code_departement' => 5, 'departement_label' => 'COLLINES'],
            ['code_departement' => 6, 'departement_label' => 'COUFFO'],
            ['code_departement' => 7, 'departement_label' => 'DONGA'],
            ['code_departement' => 8, 'departement_label' => 'LITTORAL'],
            ['code_departement' => 9, 'departement_label' => 'MONO'],
            ['code_departement' => 10, 'departement_label' => 'OUEME'],
            ['code_departement' => 11, 'departement_label' => 'PLATEAU'],
            ['code_departement' => 12, 'departement_label' => 'ZOU'],
        ]);
    }
}
