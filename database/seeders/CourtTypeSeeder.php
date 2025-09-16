<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourtTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $court_type = [
            'Олий суд',
            'Умумюрисдикция суди',
            'Маъмурий суд',
            'Ҳарбий суд'
        ];

        foreach ($court_type as $court_types) {
            DB::table('court_types')->insert([  // Use the correct table name here
                'name' => $court_types,
            ]);
        }
    }
}
