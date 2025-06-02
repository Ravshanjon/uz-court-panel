<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NationalsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $national = [
            'Ўзбек',
            'Қорақалпоқ',
            'Татар',
            'Тожик',
            'Қозоқ',
            'Туркман',
            'Бошқа',
            'Қирғиз',
            'Рус',
        ];

        foreach ($national as $nationals) {
            DB::table('nationalities')->insert([  // Use the correct table name here
                'name' => $nationals,
            ]);
        }
    }
}
