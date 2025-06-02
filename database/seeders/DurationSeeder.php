<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $duration = [
            '5 йил',
            '10 йил',
            'Ваколат муддатининг қолган даврига',
            'Ваколат муддатининг 5 йилига',
            'Олдинги ваколат муддатига',
            'Муддатсиз',
        ];

        foreach ($duration as $durations) {
            DB::table('durations')->insert([
                'name' => $durations,
            ]);
        }
    }
}
