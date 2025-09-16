<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourtSpecialty extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $speciality = [
            'Жиноят',
            'Фуқаролик',
            'Маъмурий',
            'Иқтисодий',
            'Тергов судьяси',
            'Харбий',
        ];

        foreach ($speciality as $specialities) {
            DB::table('court_specialities')->insert([  // Use the correct table name here
                'name' => $specialities,
            ]);
        }
    }
}
