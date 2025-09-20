<?php

namespace Database\Seeders;

use App\Models\Circonscription;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CirconscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $circonscriptions = [
            ['code_circ' => 2, 'lib_circ' => '2EME CIRCONSCRIPTION ELECTORALE', 'code_dep' => 1, 'lib_iso' => '2EME'],
            ['code_circ' => 1, 'lib_circ' => '1ERE CIRCONSCRIPTION ELECTORALE', 'code_dep' => 1, 'lib_iso' => '1ERE'],
            ['code_circ' => 3, 'lib_circ' => '3EME CIRCONSCRIPTION ELECTORALE', 'code_dep' => 2, 'lib_iso' => '3EME'],
            ['code_circ' => 4, 'lib_circ' => '4EME CIRCONSCRIPTION ELECTORALE', 'code_dep' => 2, 'lib_iso' => '4EME'],
            ['code_circ' => 6, 'lib_circ' => '6EME CIRCONSCRIPTION ELECTORALE', 'code_dep' => 3, 'lib_iso' => '6EME'],
            ['code_circ' => 5, 'lib_circ' => '5EME CIRCONSCRIPTION ELECTORALE', 'code_dep' => 3, 'lib_iso' => '5EME'],
            ['code_circ' => 7, 'lib_circ' => '7EME CIRCONSCRIPTION ELECTORALE', 'code_dep' => 4, 'lib_iso' => '7EME'],
            ['code_circ' => 8, 'lib_circ' => '8EME CIRCONSCRIPTION ELECTORALE', 'code_dep' => 4, 'lib_iso' => '8EME'],
            ['code_circ' => 9, 'lib_circ' => '9EME CIRCONSCRIPTION ELECTORALE', 'code_dep' => 5, 'lib_iso' => '9EME'],
            ['code_circ' => 10, 'lib_circ' => '10EME CIRCONSCRIPTION ELECTORALE', 'code_dep' => 5, 'lib_iso' => '10EME'],
            ['code_circ' => 11, 'lib_circ' => '11EME CIRCONSCRIPTION ELECTORALE', 'code_dep' => 6, 'lib_iso' => '11EME'],
            ['code_circ' => 12, 'lib_circ' => '12EME CIRCONSCRIPTION ELECTORALE', 'code_dep' => 6, 'lib_iso' => '12EME'],
            ['code_circ' => 14, 'lib_circ' => '14EME CIRCONSCRIPTION ELECTORALE', 'code_dep' => 7, 'lib_iso' => '14EME'],
            ['code_circ' => 13, 'lib_circ' => '13EME CIRCONSCRIPTION ELECTORALE', 'code_dep' => 7, 'lib_iso' => '13EME'],
            ['code_circ' => 15, 'lib_circ' => '15EME CIRCONSCRIPTION ELECTORALE', 'code_dep' => 8, 'lib_iso' => '15EME'],
            ['code_circ' => 16, 'lib_circ' => '16EME CIRCONSCRIPTION ELECTORALE', 'code_dep' => 8, 'lib_iso' => '16EME'],
            ['code_circ' => 17, 'lib_circ' => '17EME CIRCONSCRIPTION ELECTORALE', 'code_dep' => 9, 'lib_iso' => '17EME'],
            ['code_circ' => 18, 'lib_circ' => '18EME CIRCONSCRIPTION ELECTORALE', 'code_dep' => 9, 'lib_iso' => '18EME'],
            ['code_circ' => 19, 'lib_circ' => '19EME CIRCONSCRIPTION ELECTORALE', 'code_dep' => 10, 'lib_iso' => '19EME'],
            ['code_circ' => 20, 'lib_circ' => '20EME CIRCONSCRIPTION ELECTORALE', 'code_dep' => 10, 'lib_iso' => '20EME'],
            ['code_circ' => 21, 'lib_circ' => '21EME CIRCONSCRIPTION ELECTORALE', 'code_dep' => 11, 'lib_iso' => '21EME'],
            ['code_circ' => 22, 'lib_circ' => '22EME CIRCONSCRIPTION ELECTORALE', 'code_dep' => 11, 'lib_iso' => '22EME'],
            ['code_circ' => 23, 'lib_circ' => '23EME CIRCONSCRIPTION ELECTORALE', 'code_dep' => 12, 'lib_iso' => '23EME'],
            ['code_circ' => 24, 'lib_circ' => '24EME CIRCONSCRIPTION ELECTORALE', 'code_dep' => 12, 'lib_iso' => '24EME'],
        ];        

        foreach ($circonscriptions as $circonscription) {
            Circonscription::create($circonscription);
        }
    }
}
