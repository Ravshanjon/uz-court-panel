<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InspectionAdultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            'Ариза (шикоят)',
            'Ариза (шикоят) ва фаолиятни ўрганиш',
            'Тақдимнома',
            'Фаолиятини ўрганиш',
            'Фаолиятини ўрганиш ва Ариза (Шикоят)',
            'Хусусий ажрим',
        ];
        foreach ($names as $name) {
            DB::table('inspection_adults')->insert([
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

}
