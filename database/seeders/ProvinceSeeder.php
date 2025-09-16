<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = [
            'Вилоят',
            'Туманлараро, туман (шаҳар)',

        ];

        foreach ($provinces as $province) {
            DB::table('provinces_districts')->insert([  // Use the correct table name here
                'name' => $province,
            ]);
        }
    }
}
