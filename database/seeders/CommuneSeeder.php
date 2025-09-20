<?php

namespace Database\Seeders;

use App\Models\Communes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommuneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $communes = [
            ['code_com' => 1, 'lib_com' => 'BANIKOARA', 'code_circ' => 2, 'code_dep' => 1],
            ['code_com' => 2, 'lib_com' => 'GOGOUNOU', 'code_circ' => 2, 'code_dep' => 1],
            ['code_com' => 3, 'lib_com' => 'KANDI', 'code_circ' => 1, 'code_dep' => 1],
            ['code_com' => 4, 'lib_com' => 'KARIMAMA', 'code_circ' => 1, 'code_dep' => 1],
            ['code_com' => 5, 'lib_com' => 'MALANVILLE', 'code_circ' => 1, 'code_dep' => 1],
            ['code_com' => 6, 'lib_com' => 'SEGBANA', 'code_circ' => 2, 'code_dep' => 1],
            ['code_com' => 7, 'lib_com' => 'BOUKOUMBE', 'code_circ' => 3, 'code_dep' => 2],
            ['code_com' => 8, 'lib_com' => 'COBLY', 'code_circ' => 3, 'code_dep' => 2],
            ['code_com' => 9, 'lib_com' => 'KEROU', 'code_circ' => 4, 'code_dep' => 2],
            ['code_com' => 10, 'lib_com' => 'KOUANDE', 'code_circ' => 4, 'code_dep' => 2],
            ['code_com' => 11, 'lib_com' => 'MATERI', 'code_circ' => 3, 'code_dep' => 2],
            ['code_com' => 12, 'lib_com' => 'NATITINGOU', 'code_circ' => 4, 'code_dep' => 2],
            ['code_com' => 13, 'lib_com' => 'OUASSA-PEHUNCO', 'code_circ' => 4, 'code_dep' => 2],
            ['code_com' => 14, 'lib_com' => 'TANGUIETA', 'code_circ' => 3, 'code_dep' => 2],
            ['code_com' => 15, 'lib_com' => 'TOUKOUNTOUNA', 'code_circ' => 4, 'code_dep' => 2],
            ['code_com' => 16, 'lib_com' => 'AB-CALAVI', 'code_circ' => 6, 'code_dep' => 3],
            ['code_com' => 17, 'lib_com' => 'ALLADA', 'code_circ' => 5, 'code_dep' => 3],
            ['code_com' => 18, 'lib_com' => 'KPOMASSE', 'code_circ' => 5, 'code_dep' => 3],
            ['code_com' => 19, 'lib_com' => 'OUIDAH', 'code_circ' => 5, 'code_dep' => 3],
            ['code_com' => 20, 'lib_com' => 'SO-AVA', 'code_circ' => 6, 'code_dep' => 3],
            ['code_com' => 21, 'lib_com' => 'TOFFO', 'code_circ' => 5, 'code_dep' => 3],
            ['code_com' => 22, 'lib_com' => 'TORI-B', 'code_circ' => 5, 'code_dep' => 3],
            ['code_com' => 23, 'lib_com' => 'ZE', 'code_circ' => 6, 'code_dep' => 3],
            ['code_com' => 24, 'lib_com' => 'BEMBEREKE', 'code_circ' => 7, 'code_dep' => 4],
            ['code_com' => 25, 'lib_com' => 'KALALE', 'code_circ' => 7, 'code_dep' => 4],
            ['code_com' => 26, 'lib_com' => 'N\'DALI', 'code_circ' => 8, 'code_dep' => 4],
            ['code_com' => 27, 'lib_com' => 'NIKKI', 'code_circ' => 7, 'code_dep' => 4],
            ['code_com' => 28, 'lib_com' => 'PARAKOU', 'code_circ' => 8, 'code_dep' => 4],
            ['code_com' => 29, 'lib_com' => 'PERERE', 'code_circ' => 8, 'code_dep' => 4],
            ['code_com' => 30, 'lib_com' => 'SINENDE', 'code_circ' => 7, 'code_dep' => 4],
            ['code_com' => 31, 'lib_com' => 'TCHAOUROU', 'code_circ' => 8, 'code_dep' => 4],
            ['code_com' => 32, 'lib_com' => 'BANTE', 'code_circ' => 9, 'code_dep' => 5],
            ['code_com' => 33, 'lib_com' => 'DASSA-ZOUME', 'code_circ' => 9, 'code_dep' => 5],
            ['code_com' => 34, 'lib_com' => 'GLAZOUE', 'code_circ' => 10, 'code_dep' => 5],
            ['code_com' => 35, 'lib_com' => 'OUESSE', 'code_circ' => 10, 'code_dep' => 5],
            ['code_com' => 36, 'lib_com' => 'SAVALOU', 'code_circ' => 9, 'code_dep' => 5],
            ['code_com' => 37, 'lib_com' => 'SAVE', 'code_circ' => 10, 'code_dep' => 5],
            ['code_com' => 38, 'lib_com' => 'APLAHOUE', 'code_circ' => 11, 'code_dep' => 6],
            ['code_com' => 39, 'lib_com' => 'DJAKOTOMEY', 'code_circ' => 11, 'code_dep' => 6],
            ['code_com' => 40, 'lib_com' => 'DOGBO', 'code_circ' => 12, 'code_dep' => 6],
            ['code_com' => 41, 'lib_com' => 'KLOUEKANMEY', 'code_circ' => 11, 'code_dep' => 6],
            ['code_com' => 42, 'lib_com' => 'LALO', 'code_circ' => 12, 'code_dep' => 6],
            ['code_com' => 43, 'lib_com' => 'TOVIKLIN', 'code_circ' => 12, 'code_dep' => 6],
            ['code_com' => 44, 'lib_com' => 'BASSILA', 'code_circ' => 14, 'code_dep' => 7],
            ['code_com' => 45, 'lib_com' => 'COPARGO', 'code_circ' => 14, 'code_dep' => 7],
            ['code_com' => 46, 'lib_com' => 'DJOUGOU', 'code_circ' => 13, 'code_dep' => 7],
            ['code_com' => 47, 'lib_com' => 'OUAKE', 'code_circ' => 14, 'code_dep' => 7],
            ['code_com' => 48, 'lib_com' => 'COT.1-6', 'code_circ' => 15, 'code_dep' => 8],
            ['code_com' => 78, 'lib_com' => 'COT.7-13', 'code_circ' => 16, 'code_dep' => 8],
            ['code_com' => 49, 'lib_com' => 'ATHIEME', 'code_circ' => 17, 'code_dep' => 9],
            ['code_com' => 50, 'lib_com' => 'BOPA', 'code_circ' => 18, 'code_dep' => 9],
            ['code_com' => 51, 'lib_com' => 'COME', 'code_circ' => 17, 'code_dep' => 9],
            ['code_com' => 52, 'lib_com' => 'GRAND-POPO', 'code_circ' => 17, 'code_dep' => 9],
            ['code_com' => 53, 'lib_com' => 'HOUEYOGBE', 'code_circ' => 18, 'code_dep' => 9],
            ['code_com' => 54, 'lib_com' => 'LOKOSSA', 'code_circ' => 18, 'code_dep' => 9],
            ['code_com' => 55, 'lib_com' => 'ADJARRA', 'code_circ' => 19, 'code_dep' => 10],
            ['code_com' => 56, 'lib_com' => 'ADJOHOUN', 'code_circ' => 20, 'code_dep' => 10],
            ['code_com' => 57, 'lib_com' => 'AGUEGUES', 'code_circ' => 19, 'code_dep' => 10],
            ['code_com' => 58, 'lib_com' => 'AKP-MISSERETE', 'code_circ' => 20, 'code_dep' => 10],
            ['code_com' => 59, 'lib_com' => 'AVRANKOU', 'code_circ' => 20, 'code_dep' => 10],
            ['code_com' => 60, 'lib_com' => 'BONOU', 'code_circ' => 20, 'code_dep' => 10],
            ['code_com' => 61, 'lib_com' => 'DANGBO', 'code_circ' => 20, 'code_dep' => 10],
            ['code_com' => 62, 'lib_com' => 'PORTO-NOVO', 'code_circ' => 19, 'code_dep' => 10],
            ['code_com' => 63, 'lib_com' => 'SEME-PODJI', 'code_circ' => 19, 'code_dep' => 10],
            ['code_com' => 64, 'lib_com' => 'ADJA-OUERE', 'code_circ' => 21, 'code_dep' => 11],
            ['code_com' => 65, 'lib_com' => 'IFANGNI', 'code_circ' => 21, 'code_dep' => 11],
            ['code_com' => 66, 'lib_com' => 'KETOU', 'code_circ' => 22, 'code_dep' => 11],
            ['code_com' => 67, 'lib_com' => 'POBE', 'code_circ' => 22, 'code_dep' => 11],
            ['code_com' => 68, 'lib_com' => 'SAKETE', 'code_circ' => 21, 'code_dep' => 11],
            ['code_com' => 69, 'lib_com' => 'ABOMEY', 'code_circ' => 23, 'code_dep' => 12],
            ['code_com' => 70, 'lib_com' => 'AGBANGNIZOUN', 'code_circ' => 23, 'code_dep' => 12],
            ['code_com' => 71, 'lib_com' => 'BOHICON', 'code_circ' => 23, 'code_dep' => 12],
            ['code_com' => 72, 'lib_com' => 'COVE', 'code_circ' => 24, 'code_dep' => 12],
            ['code_com' => 73, 'lib_com' => 'DJIDJA', 'code_circ' => 23, 'code_dep' => 12],
            ['code_com' => 74, 'lib_com' => 'OUINHI', 'code_circ' => 24, 'code_dep' => 12],
            ['code_com' => 75, 'lib_com' => 'ZA-KPOTA', 'code_circ' => 24, 'code_dep' => 12],
            ['code_com' => 76, 'lib_com' => 'ZAGNANADO', 'code_circ' => 24, 'code_dep' => 12],
            ['code_com' => 77, 'lib_com' => 'ZOGBODOMEY', 'code_circ' => 24, 'code_dep' => 12],
        ];        

        foreach ($communes as $commune) {
            Communes::create($commune);
        }
    }
}
