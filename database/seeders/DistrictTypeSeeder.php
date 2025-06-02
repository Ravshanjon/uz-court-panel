<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $district_type = [
            'Туман',
            'Туманлараро',
            'Шахар',
        ];

        foreach ($district_type as $district_types) {
            DB::table('district_types')->insert([  // Use the correct table name here
                'name' => $district_types,
            ]);
        }
    }
}
