<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CirconscriptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('circonscriptions')->insert([
            ['code_circonscription' => 2, 'circonscription_label' => '1ERE CIRCONSCRIPTION ELECTORALE', 'code_departement' => 1, 'lib_iso' => '1ERE'],
            ['code_circonscription' => 1, 'circonscription_label' => '2EME CIRCONSCRIPTION ELECTORALE', 'code_departement' => 1, 'lib_iso' => '2EME'],
            ['code_circonscription' => 3, 'circonscription_label' => '3EME CIRCONSCRIPTION ELECTORALE', 'code_departement' => 2, 'lib_iso' => '3EME'],
            ['code_circonscription' => 4, 'circonscription_label' => '4EME CIRCONSCRIPTION ELECTORALE', 'code_departement' => 2, 'lib_iso' => '4EME'],
            ['code_circonscription' => 6, 'circonscription_label' => '6EME CIRCONSCRIPTION ELECTORALE', 'code_departement' => 3, 'lib_iso' => '6EME'],
            ['code_circonscription' => 5, 'circonscription_label' => '5EME CIRCONSCRIPTION ELECTORALE', 'code_departement' => 3, 'lib_iso' => '5EME'],
            ['code_circonscription' => 7, 'circonscription_label' => '7EME CIRCONSCRIPTION ELECTORALE', 'code_departement' => 4, 'lib_iso' => '7EME'],
            ['code_circonscription' => 8, 'circonscription_label' => '8EME CIRCONSCRIPTION ELECTORALE', 'code_departement' => 4, 'lib_iso' => '8EME'],
            ['code_circonscription' => 9, 'circonscription_label' => '9EME CIRCONSCRIPTION ELECTORALE', 'code_departement' => 5, 'lib_iso' => '9EME'],
            ['code_circonscription' => 10, 'circonscription_label' => '10EME CIRCONSCRIPTION ELECTORALE', 'code_departement' => 5, 'lib_iso' => '10EME'],
            ['code_circonscription' => 11, 'circonscription_label' => '11EME CIRCONSCRIPTION ELECTORALE', 'code_departement' => 6, 'lib_iso' => '11EME'],
            ['code_circonscription' => 12, 'circonscription_label' => '12EME CIRCONSCRIPTION ELECTORALE', 'code_departement' => 6, 'lib_iso' => '12EME'],
            ['code_circonscription' => 14, 'circonscription_label' => '14EME CIRCONSCRIPTION ELECTORALE', 'code_departement' => 7, 'lib_iso' => '14EME'],
            ['code_circonscription' => 13, 'circonscription_label' => '13EME CIRCONSCRIPTION ELECTORALE', 'code_departement' => 7, 'lib_iso' => '13EME'],
            ['code_circonscription' => 15, 'circonscription_label' => '15EME CIRCONSCRIPTION ELECTORALE', 'code_departement' => 8, 'lib_iso' => '15EME'],
            ['code_circonscription' => 16, 'circonscription_label' => '16EME CIRCONSCRIPTION ELECTORALE', 'code_departement' => 8, 'lib_iso' => '16EME'],
            ['code_circonscription' => 17, 'circonscription_label' => '17EME CIRCONSCRIPTION ELECTORALE', 'code_departement' => 9, 'lib_iso' => '17EME'],
            ['code_circonscription' => 18, 'circonscription_label' => '18EME CIRCONSCRIPTION ELECTORALE', 'code_departement' => 9, 'lib_iso' => '18EME'],
            ['code_circonscription' => 19, 'circonscription_label' => '19EME CIRCONSCRIPTION ELECTORALE', 'code_departement' => 10, 'lib_iso' => '19EME'],
            ['code_circonscription' => 20, 'circonscription_label' => '20EME CIRCONSCRIPTION ELECTORALE', 'code_departement' => 10, 'lib_iso' => '20EME'],
            ['code_circonscription' => 21, 'circonscription_label' => '21EME CIRCONSCRIPTION ELECTORALE', 'code_departement' => 11, 'lib_iso' => '21EME'],
            ['code_circonscription' => 22, 'circonscription_label' => '22EME CIRCONSCRIPTION ELECTORALE', 'code_departement' => 11, 'lib_iso' => '22EME'],
            ['code_circonscription' => 23, 'circonscription_label' => '23EME CIRCONSCRIPTION ELECTORALE', 'code_departement' => 12, 'lib_iso' => '23EME'],
            ['code_circonscription' => 24, 'circonscription_label' => '24EME CIRCONSCRIPTION ELECTORALE', 'code_departement' => 12, 'lib_iso' => '24EME'],
        ]);
    }
}
