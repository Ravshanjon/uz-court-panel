<?php

namespace Database\Seeders;

use App\Models\SupermeJudges;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupremeJudgesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $judges = [
            'Х.Камолов',
            'И.Хакимов',
            'В.Файзуллаев',
            'Б.Сабиров',
            'О.Мирзамахмудов',
            'М.Каландарова',
            'М.Кукиев',
        ];

        foreach ($judges as $name) {
            SupermeJudges::create([
                'name' => $name,
            ]);
        }
    }
}
