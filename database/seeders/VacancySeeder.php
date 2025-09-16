<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VacancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vacancy = [
            'Янги штат',
            'Ўрганишда',
        ];

        foreach ($vacancy as $vacancy_stat) {
            DB::table('vacancy_statuses')->insert([  // Use the correct table name here
                'name' => $vacancy_stat,
            ]);
        }
    }
}
