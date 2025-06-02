<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JudgesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $judges = [
            'Б.Сабиров',
            'В.Файзуллаев',
            'И.Хакимов',
            'М.Қаландарова',
            'М.Кукиев',
            'О.Мирзамаҳмаудов',
            'Х.Камолов',
        ];

        foreach ($judges as $judge) {
            DB::table('judges')->insert([
                'name' => $judge,
            ]);
        }
    }
}
